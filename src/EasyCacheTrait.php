<?php

namespace App\Traits;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

trait EasyCacheTrait
{
    /**
     * default expire time (min)
     * @var int
     */
    private $default_expire = 60;

    /**
     * cache through __call
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $cacheKey = self::_getCacheKey($method, $parameters);
        $expire = $this->getExpire($method);
        $closure = function () use ($method, $parameters) {
            return call_user_func_array([$this, $method], $parameters);
        };
        try {
            if (config('app.debug') && Input::get('skipCache')) {
                return $closure();
            }
            if (config('app.debug') && Input::get('forgetCache')) {
                Cache::forget($cacheKey);
            }
            //do not cache when expire is empty
            if ($expire) {
                $result = Cache::remember($cacheKey, $expire, $closure);
                return $result;
            } else {
                return $closure();
            }
        } catch (\Throwable $throwable) {
            Log::error($throwable);
            return $closure();
        }
    }

    /**
     * get expire time by reflection
     * @param $method
     * @return int
     */
    private function getExpire($method)
    {
        $rf = new \ReflectionMethod($this, $method);
        $staticVar = $rf->getStaticVariables();
        if (array_key_exists('expire', $staticVar)) {
            $expire = $staticVar['expire'];
        } else {
            $expire = $this->default_expire;
        }
        return $expire;
    }

    /**
     * delete all cache from the class
     * @return bool
     */
    public function forgetCache()
    {
        $cacheName = self::_getCacheKeyPrefixLevel1() . '*';
        $redis = Cache::getRedis();
        $keys = $redis->keys($cacheName);
        if ($keys) {
            $redis->del($keys);
        };
        return true;
    }

    /**
     * delete all cache from method
     * @param $method
     * @return bool
     */
    public function forgetMethodCache($method){
        $cacheName = self::_getCacheKeyPrefixLevel2($method) . '*';
        $redis = Cache::getRedis();
        $keys = $redis->keys($cacheName);
        if ($keys) {
            $redis->del($keys);
        };
        return true;
    }

    /**
     * get cache level1 prefix
     * @return string
     */
    private static function _getCacheKeyPrefixLevel1(){
        return __CLASS__ . ":";
    }

    /**
     * get cache level2 prefix
     * @param $method
     * @return string
     */
    private static function _getCacheKeyPrefixLevel2($method){
        return self::_getCacheKeyPrefixLevel1() . $method . ":";
    }

    /**
     * get cache key
     * @param $method
     * @param $params
     * @return string
     */
    private static function _getCacheKey($method, $params){
        return self::_getCacheKeyPrefixLevel2($method) . md5(json_encode($params));
    }


}