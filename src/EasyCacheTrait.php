<?php

namespace App\Traits;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;

trait CacheProtectFunction
{
    /**
     * @param $method
     * @param $parameters
     * @return mixed
     * cache through __call
     */
    public function __call($method, $parameters)
    {
        $cacheName = $method . "_" . md5(json_encode($parameters));

        $expire = $this->getExpire($method);

        $closure = function () use ($method, $parameters) {
            return call_user_func_array([$this, $method], $parameters);
        };

        if (config('app.debug') && Input::get('skipCache')) {
            return $closure();
        }

        if (config('app.debug') && Input::get('forgetCache')) {
            Cache::forget($cacheName);
        }

        $result = Cache::remember($cacheName, $expire, $closure);

        return $result;
    }

    /**
     * @param $method
     * @return int
     * get expire by reflection
     */
    private function getExpire($method)
    {
        $rf = new \ReflectionMethod($this,$method);
        $staticVar = $rf->getStaticVariables();
        if (array_key_exists('expire',$staticVar)){
            $expire = $staticVar['expire'];
        } else {
            $expire = 60;
        }
        return $expire;
    }
}