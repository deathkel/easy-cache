

# A Trait to use cache easily for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/deathkel/easy-cache.svg?style=flat-square)](https://packagist.org/packages/deathkel/easy-cache)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/deathkel/easy-cache/master.svg?style=flat-square)](https://travis-ci.org/deathkel/easy-cache)
[![Quality Score](https://img.shields.io/scrutinizer/g/deathkel/easy-cache.svg?style=flat-square)](https://scrutinizer-ci.com/g/deathkel/easy-cache)
[![Total Downloads](https://img.shields.io/packagist/dt/deathkel/easy-cache.svg?style=flat-square)](https://packagist.org/packages/deathkel/easy-cache)

## INSTALL
`composer require deathkel/easy-cache`

## USAGE
* this trait will auto cache protect function
* default cache time is 60 minutes. you can define a `static` variable `$expire` for each function

```php
public class test(){

    use EasyCacheTrait;

    public function DontWantToBeCache(){ // public function will not be cached
        //.....
    }

    protected function WantToBeCache(){ // protected function will be cached automatically
        static $expire = 60; //minute that this function want to be cached
    }
}
```
### when in debug pattern
* add 'skipCache=1' to http query param will skip cache and exec function
* add 'forgetCache=1' to http query param will forget cache and restore cache

## TODO
* add test example

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.