<?php

use Owenoj\LaravelGetId3\GetId3;

if (!function_exists('object2Array')) {
    function object2Array($array)
    {
        if (is_object($array)) {
            $array = (array) $array;
        }
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $array[$key] = object2Array($value);
            }
        }
        return $array;
    }
}



if (!function_exists('camelize')) {
    function camelize($uncamelizedWords, $separator = '_')
    {
        $uncamelizedWords = $separator . str_replace($separator, " ", strtolower($uncamelizedWords));
        return ltrim(str_replace(" ", "", ucwords($uncamelizedWords)), $separator);
    }
}


if (!function_exists('transformTime')) {
    function transformTime($time)
    {
        $hour = floor($time / 3600) ?: "00";
        $min =  floor(($time - $hour * 3600) / 60) ?: "00";
        $second = ($time - $hour * 3600 - $min * 60) ?: "00";
        return  sprintf("%02d:%02d:%02d", $hour, $min, $second);
    }
}


if (!function_exists('getResourceTotalTime')) {
    function getResourceTotalTime($filename)
    {
        $track = GetId3::fromDiskAndPath('public', "/" . $filename);
        $total_time = $track->getPlaytime();
        $data = explode(":", $total_time);
        switch (count($data)) {
            case 3:
                $total_time =  $data[0] * 3600 + $data[1] * 60 + $data[1];
                break;
            case 2:
                $total_time =  $data[0] * 60 + $data[1];
                break;
            case 1:
                $total_time =  $data[0];
                break;
            default:
                $total_time =  0;
        }
        return $total_time;
    }
}


if (!function_exists('generateShareCode')) {
    function generateShareCode($num)
    {
        // $charSet = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charSet = 'XAE4ZS7JBCD3FGH8KLMWN9PQR6TU2VY';
        // 个位以内，直接返回
        if ($num < strlen($charSet)) {
            return substr($charSet, $num, 1);
        }
        // 递归个位以上的部分
        $high = floor($num / strlen($charSet));
        $unit = $num % strlen($charSet);
        return generateShareCode($high) . substr($charSet, $unit, 1);
    }
}


if (!function_exists('encryptUrl')) {
    function encryptUrl($origin_url, $key)
    {
        $key = md5($key);
        return base64_encode(openssl_encrypt($origin_url, 'AES-256-ECB', $key, OPENSSL_RAW_DATA));
    }
}


if (!function_exists('decryptUrl')) {
    function decryptUrl($url, $key)
    {
        $key = md5($key);
        return openssl_decrypt(base64_decode($url), 'AES-256-ECB', $key, OPENSSL_RAW_DATA);
    }
}



if (!function_exists('makeTree')) {
    function makeTree($data, $node_id)
    {
        $tree = [];
        if(count($data) == 0){
            return $tree;
        }
        foreach ($data as  &$item) {
            $parent_id = $item['parent_id'];
            if (isset($data[$parent_id])) {
                $tree[$parent_id] = &$data[$parent_id];
            } else {
                $tree[$parent_id] = ['children' => []];
            }
            $tree[$parent_id]['children'][] = &$item;
        }
        return $tree[$node_id]['children'];
    }
}

if (!function_exists('flattenTree')) {
    function flattenTree($tree, $parent_index = '')
    {
        $result = [];
        if(count($tree) == 0){
            return $tree;
        }
        foreach($tree as $key=>$item){
            $num =  $key +1 ;
            $index =  $parent_index ? $parent_index .'.'.  $num  : $num ;
            $tmp = [
                'index'       => $index,
                'description' => $item->description,
                'created_at'  => $item->created_at->toDateTimeString(),
                'name'        => $item->label,
                'amount'      => $item->pivot ? $item->pivot->amount : 1,
                'files'       => $item->files->map(function($file)use($item){
                    return [
                        'id'       => $file->id,
                        'path'     => $file->path,
                        'url'      => $file->url,
                        'name'     => $item->label,
                        'filename' => $file->filename,
                    ];
                })->toArray(),
            ];
            $result[] = $tmp;
            if (isset($item->children) &&  count($item->children) >0){
                $result = array_merge($result,flattenTree($item->children,  $index));
            }
        }
        return $result;
    }
}




if (!function_exists('formatMoney')) {
    function formatMoney($money, $decimal =2)
    {
        $num = floatval($money);
        return $num ? number_format($num,$decimal) : 0;
    }
}