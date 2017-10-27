<?php

namespace App\Traits;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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
        $cacheName = __CLASS__ . ":" . $method . "_" . md5(json_encode($parameters));

        $expire = $this->getExpire($method);

        $closure = function () use ($method, $parameters) {
            return call_user_func_array([$this, $method], $parameters);
        };
        try {
            if (config('app.debug') && Input::get('skipCache')) {
                return $closure();
            }

            if (config('app.debug') && Input::get('forgetCache')) {
                Cache::forget($cacheName);
            }

            //do not cache when expire is empty
            if ($expire) {
                $result = Cache::remember($cacheName, $expire, $closure);
                return $result;
            } else {
                return $closure();
            }
        }catch (\Throwable $throwable){
            Log::error($throwable);
            return $closure();
        }
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

    /**
     * @return bool
     * delete all cache from the class
     * TODO support other driver
     */
    public function forgetCache(){
        $cacheName = __CLASS__ . ':*';

        $redis = Cache::getRedis();
        $keys = $redis->keys($cacheName);
        if ($keys) {
            $redis->del($keys);
        };

        return true;
    }
}