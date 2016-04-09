<?php

require_once __DIR__.'/../vendor/autoload.php';

class UrlComposerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException UrlComposer\Exceptions\UrlException
     */
    public function test_constuctor_withBadUrl_thrownsUrlException()
    {
        $badUrl = 'htt:badURL';
        new UrlComposer\UrlComposer($badUrl);
    }

    public function test_compose_withTrailingSlashURL_returnsUrlWithoutTrailingSlash()
    {
        $badUrl = 'http://url-with-trailing-slash/';
        $expectedComposedUrl = 'http://url-with-trailing-slash';
        $url = new UrlComposer\UrlComposer($badUrl);
        $this->assertEquals($expectedComposedUrl, $url->compose());
    }

    public function test_compose_returnsValidURL()
    {
        $baseUlr = 'http://my-url.com/';
        $expectedComposedUrl = 'http://my-url.com/one/two/'.urlencode('tëst');

        $url = new \UrlComposer\UrlComposer($baseUlr);
        $url->addToUrl('one')->addToUrl('two')->addToUrl('tëst');
        var_dump($url->compose());
        $this->assertEquals($expectedComposedUrl, $url->compose());
    }

    /**
     * @expectedException UrlComposer\Exceptions\UrlException
     */
    public function test_addToUrl_withBadChunck_thrownsUrlException()
    {
        $baseUlr = 'http://my-url.com/';
        $badChunck = 3;
        $url = new \UrlComposer\UrlComposer($baseUlr);
        $url->addToUrl($badChunck);
    }

    /**
     * @expectedException UrlComposer\Exceptions\UrlException
     */
    public function test_addToUrl_withEmptyString_thrownsUrlException()
    {
        $baseUlr = 'http://my-url.com/';
        $badChunck = '';
        $url = new \UrlComposer\UrlComposer($baseUlr);
        $url->addToUrl($badChunck);
    }

}