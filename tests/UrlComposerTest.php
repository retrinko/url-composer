<?php

require_once __DIR__ . '/../vendor/autoload.php';

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

    /**
     * @expectedException UrlComposer\Exceptions\UrlException
     */
    public function test_compose_withUnicodeHost_thrownsUrlException()
    {
        $unicode = 'http://piña.com';
        new UrlComposer\UrlComposer($unicode);
    }

    /**
     * @@expectedException UrlComposer\Exceptions\UrlException
     */
    public function test_construct_withEmtyUrl_thrownsUrlExceptionOnComposition()
    {
        $urlComposer = new UrlComposer\UrlComposer();
        $urlComposer->compose();
    }

    public function test_compose_withPynicodeHost_returnsValidURL()
    {
        $punycode = 'http://'.idn_to_ascii('piña.com');
        $expectedComposedUrl = 'http://xn--pia-8ma.com';
        $url = new UrlComposer\UrlComposer($punycode);
        $this->assertEquals($expectedComposedUrl, $url->compose());
    }

    public function test_compose_returnsValidURL()
    {
        $baseUlr = 'http://my-url.com?key=10&a=b#fragment';
        $expectedComposedUrl = 'http://my-url.com/one/two/' . urlencode('tëst')
                               . '?key=10&a=b#fragment';

        $url = new \UrlComposer\UrlComposer($baseUlr);
        $url->addToPath('one')->addToPath('two')->addToPath('tëst');
        $this->assertEquals($expectedComposedUrl, $url->compose());
    }

    /**
     * @expectedException UrlComposer\Exceptions\UrlException
     */
    public function test_addToPath_withBadPathPart_thrownsUrlException()
    {
        $baseUlr = 'http://my-url.com/';
        $badPathPart = 3;
        $url = new \UrlComposer\UrlComposer($baseUlr);
        $url->addToPath($badPathPart);
    }

    /**
     * @expectedException UrlComposer\Exceptions\UrlException
     */
    public function test_addToPath_withEmptyString_thrownsUrlException()
    {
        $baseUlr = 'http://my-url.com/';
        $emptyPathPart = '';
        $url = new \UrlComposer\UrlComposer($baseUlr);
        $url->addToPath($emptyPathPart);
    }

    /**
     * @expectedException UrlComposer\Exceptions\UrlException
     */
    public function test_addToQuery_withEmptyQueyKey_thrownsUrlException()
    {
        $baseUlr = 'http://my-url.com/';
        $badQueKey = '';
        $url = new \UrlComposer\UrlComposer($baseUlr);
        $url->addToQuery($badQueKey, 'value');
    }

    public function test_setters()
    {
        $expected = 'https://retrinko:123456@xn--pia-8ma.com:80/one/two/three?key=val#fragment';
        $urlComposer = new UrlComposer\UrlComposer('');
        $urlComposer->setScheme('https')
            ->setHost('piña.com')
            ->setUser('retrinko')
            ->setPass('123456')
            ->setPort(80)
            ->setPath(['one', 'two', 'three'])
            ->setQuery(['key'=>'val'])
            ->setFragment('fragment');
        $url = $urlComposer->compose();
        $this->assertEquals($expected, $url);
    }

    public function test_toString_withProperURL_returnsString()
    {
        $uc = new \UrlComposer\UrlComposer('http://hello.com/test/to-string');
        $result = $uc->__toString();
        $this->assertTrue(is_string($result));
    }

    public function test_toString_withExceptions_returnsString()
    {
        $uc = new \UrlComposer\UrlComposer();
        $result = $uc->__toString();
        $this->assertTrue(is_string($result));
    }
}