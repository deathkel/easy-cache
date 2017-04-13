

# A Trait to use cache easily for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/deathkel/EasyCache.svg?style=flat-square)](https://packagist.org/packages/deathkel/EasyCache)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/deathkel/EasyCachemaster.svg?style=flat-square)](https://travis-ci.org/deathkel/EasyCache)
[![Quality Score](https://img.shields.io/scrutinizer/g/deathkel/EasyCache.svg?style=flat-square)](https://scrutinizer-ci.com/g/deathkel/EasyCache)
[![Total Downloads](https://img.shields.io/packagist/dt/deathkel/EasyCache.svg?style=flat-square)](https://packagist.org/packages/deathkel/EasyCache)

## USAGE
* this trait will auto cache protect function
* default cache time is 60 minutes. you can define a `static` variable `$expire` for each function

```php
public class test(){

    use EasyCacheTrait;

    public function DontWantToBeCache(){ // public function will not be cached
        //.....
    }

    protect function WantToBeCache(){ // protect function will be cached automatically
        static $expire = 60; //minute that this function want to be cached
    }
}
```
### when in debug pattern
* add 'skipCache' to http query param will skip cache and exec function
* add 'forgetCache' to http query param will forget cache and restore cache

## TODO
* add test example

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.