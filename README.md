[![Build Status](https://travis-ci.org/retrinko/url-composer.svg?branch=master)](https://travis-ci.org/retrinko/url-composer)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/retrinko/url-composer/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/retrinko/url-composer/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/retrinko/url-composer/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/retrinko/url-composer/?branch=master)

# retrinko/url-composer

Basic URL composer for PHP.

##  Installation

Install the latest version with

    $ composer require retrinko/url-composer
    
## Dependencies

This library requires the php's intl extension to work.

##  Basic usage

    <?php
    use Retrinko|UrlComposer\UrlComposer;
    
    try
    {
        $urlComposer = new UrlComposer('http://my-url.com');
        $urlComposer->addToUrl('blog')->addToQuery('id', 25);
        $url = $urlComposer->compose();
        printf('URL: %s' . PHP_EOL, $url);
    }
    catch (\Exception $e)
    {
        printf('Exception!: %s' . PHP_EOL, $e->getMessage());
    }
