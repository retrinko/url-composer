<?php

namespace Retrinko\UrlComposer\Tests\Unit;

use Retrinko\UrlComposer\UrlComposer;

class UrlComposerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Retrinko\UrlComposer\Exceptions\UrlException
     */
    public function test_constuctor_withBadUrl_thrownsUrlException()
    {
        $badUrl = 'htt:badURL';
        new UrlComposer($badUrl);
    }

    public function test_compose_withTrailingSlashURL_returnsUrlWithoutTrailingSlash()
    {
        $badUrl = 'http://url-with-trailing-slash/';
        $expectedComposedUrl = 'http://url-with-trailing-slash';
        $url = new UrlComposer($badUrl);
        $this->assertEquals($expectedComposedUrl, $url->compose());
    }

    public function test_compose_withPathAndTrailingSlashURL_returnsUrlWithoutTrailingSlash()
    {
        $urlwithPath = 'http://url-with-trailing-slash.com/one/two/';
        $expectedComposedUrl = 'http://url-with-trailing-slash.com/one/two';
        $url = new UrlComposer($urlwithPath);
        $this->assertEquals($expectedComposedUrl, $url->compose());
    }

    /**
     * @expectedException \Retrinko\UrlComposer\Exceptions\UrlException
     */
    public function test_compose_withUnicodeHost_thrownsUrlException()
    {
        $unicode = 'http://piña.com';
        new UrlComposer($unicode);
    }

    /**
     * @@expectedException \Retrinko\UrlComposer\Exceptions\UrlException
     */
    public function test_construct_withEmtyUrl_thrownsUrlExceptionOnComposition()
    {
        $urlComposer = new UrlComposer();
        $urlComposer->compose();
    }

    public function test_compose_withPunycodeHost_returnsValidURL()
    {
        $punycode = 'http://'.idn_to_ascii('piña.com');
        $expectedComposedUrl = 'http://xn--pia-8ma.com';
        $url = new UrlComposer($punycode);
        $this->assertEquals($expectedComposedUrl, $url->compose());
    }

    public function test_compose_returnsValidURL()
    {
        $baseUlr = 'http://my-url.com/api/?key=10&a=b#fragment';
        $expectedComposedUrl = 'http://my-url.com/api/one/two/' . urlencode('tëst')
                               . '?key=10&a=b#fragment';

        $url = new UrlComposer($baseUlr);
        $url->addToPath('one')->addToPath('two')->addToPath('tëst');
        $this->assertEquals($expectedComposedUrl, $url->compose());
    }

    /**
     * @expectedException \Retrinko\UrlComposer\Exceptions\UrlException
     */
    public function test_addToPath_withBadPathPart_thrownsUrlException()
    {
        $baseUlr = 'http://my-url.com/';
        $badPathPart = 3;
        $url = new UrlComposer($baseUlr);
        $url->addToPath($badPathPart);
    }

    /**
     * @expectedException \Retrinko\UrlComposer\Exceptions\UrlException
     */
    public function test_addToPath_withEmptyString_thrownsUrlException()
    {
        $baseUlr = 'http://my-url.com/';
        $emptyPathPart = '';
        $url = new UrlComposer($baseUlr);
        $url->addToPath($emptyPathPart);
    }

    /**
     * @expectedException \Retrinko\UrlComposer\Exceptions\UrlException
     */
    public function test_addToQuery_withEmptyQueyKey_thrownsUrlException()
    {
        $baseUlr = 'http://my-url.com/';
        $badQueKey = '';
        $url = new UrlComposer($baseUlr);
        $url->addToQuery($badQueKey, 'value');
    }

    public function test_setters()
    {
        $expected = 'https://retrinko:123456@xn--pia-8ma.com:80/one/two/three?key=val#fragment';
        $urlComposer = new UrlComposer('');
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

    public function test_toString_withProperURL_returnsProperString()
    {
        $expected = 'http://hello.com/test/to-string';
        $uc = new UrlComposer($expected);
        $result = $uc->__toString();
        $this->assertTrue(is_string($result));
        $this->assertEquals($expected, $result);
    }

    public function test_toString_withExceptions_returnsString()
    {
        $uc = new UrlComposer();
        $result = $uc->__toString();
        $this->assertTrue(is_string($result));
    }
}