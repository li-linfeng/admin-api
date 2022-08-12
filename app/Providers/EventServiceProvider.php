<?php

namespace App\Providers;


use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use App\Response\FormatResponse;
use Dingo\Api\Event\ResponseWasMorphed;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [

        'Illuminate\Database\Events\QueryExecuted'            => [
            'App\Listeners\QueryListener',
        ],
        // 监听 Dingo API发送响应之前对响应进行转化的事件
        ResponseWasMorphed::class => [
            FormatResponse::class
        ],
    ];


    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
