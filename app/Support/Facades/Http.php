<?php
namespace App\Support\Facades;

class Http extends \Illuminate\Support\Facades\Http
{
    public static function post($url, $data = [])
    {
        if (env('APP_ENV') === 'local') {
            return static::withOptions(config('http_client.default'))->post($url, $data);
        }
        return static::withOptions([])->post($url, $data);
    }
    public static function get($url, $data = [])
    {
        if (env('APP_ENV') === 'local') {
            return static::withOptions(config('http_client.default'))->get($url, $data);
        }
        return static::withOptions([])->get($url, $data);

    }
    public static function withQueryParameters($parameters)
    {
        if (env('APP_ENV') === 'local') {
            dd(env('APP_ENV'));
            dd($parameters);
            return static::withOptions(config('http_client.default'))->withQueryParameters($parameters);
        }
        // dd(env('APP_ENV'));

        return static::withOptions([])->withQueryParameters($parameters);
    }
}
