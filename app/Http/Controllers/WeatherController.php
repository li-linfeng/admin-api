<?php

namespace App\Http\Controllers;

use App\Http\Transformers\TodoTransformer;
use App\Models\OrderItem;
use App\Models\PreSaleRequest;
use App\Models\SaleRequest;
use App\Models\Todo;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WeatherController extends Controller
{
    
    public function weather(Request $request )
    {
       //获取ip
       $ip = $request->input('use_ip') ? $request->input('ip') : $request->getClientIp();

       if ($ip == '127.0.0.1'){
         $ip = "113.110.197.234";
       }

       $city_result = Cache::rememberForever($ip, function () use($ip) {
            //获取高德city code
            $key = config('weather.key');
            $query = [
                'key' => $key,
                'ip'=>$ip
            ];
          return $this->query('ip', $query);
        });

        $city_weather = Cache::remember($ip.':weather', 6*60*60, function () use($city_result) {
            //获取高德city code
            $key = config('weather.key');
            $q = [
                'key' => $key,
                'city'=>$city_result['adcode'],
                'extensions'=> 'base'
            ];
          return $this->query('weather/weatherInfo', $q);
        });
   
        return $this->response()->array( $city_weather);

    }


    public function todo(Request $request, TodoTransformer $todoTransformer)
    {

      $todos = Todo::where('user_id', auth('api')->id())
        ->where('type', $request->input('type', 'pre_sale'))
        ->where('is_read',0)
        ->paginate($request->input('per_page',10));
      return $this->response()->paginator($todos, $todoTransformer);
    }

    public function read(Todo $todo)
    {
      $todo->update(['is_read'=>1]);
      return $this->response()->noContent();
    }

    public function readAll()
    {
      Todo::where('user_id', auth('api')->id())->update(['is_read'=>1]);

      return $this->response()->noContent();
    }


       protected function query($uri, $params)
       {
            $client = New Client([
                'base_uri' => 'https://restapi.amap.com/v3/',
                ]
            );
            $response = $client->request('GET', $uri, [
                'query' => $params ,
                'verify' => false
                ]);
        
            $body = $response->getBody()->getContents();
            $re = json_decode($body, true);
            return $re;
       }

}
