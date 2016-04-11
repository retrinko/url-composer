# retrinko/url-composer

Basic URL composer for PHP.

##  Installation

Install the latest version with

    $ composer require retrinko/url-composer
    
## Dependencies

This library requires the php's intl extension to work.

##  Basic usage

    <?php
    use UrlComposer\UrlComposer;
    
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
