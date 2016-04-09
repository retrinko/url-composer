<?php

namespace UrlComposer;

use UrlComposer\Exceptions\UrlException;

class UrlComposer
{
    const URL_SEPARATOR = '/';

    /**
     * @var string
     */
    protected $baseUrl;
    /**
     * @var array
     */
    protected $urlAddons = [];
    /**
     * @var array
     */
    protected $query = [];

    /**
     * Url constructor.
     *
     * @param string $baseUrl
     *
     * @throws UrlException
     */
    public function __construct($baseUrl = '')
    {
        if (false == filter_var($baseUrl, FILTER_VALIDATE_URL))
        {
            throw new UrlException(sprintf('Invalid URL! (%s)', $baseUrl));
        }
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    /**
     * @param string $string
     *
     * @return $this
     * @throws UrlException
     */
    public function addToUrl($string)
    {
        if (!is_string($string))
        {
            throw new UrlException('Invalid URL chunck! URL chunks must be strings.');
        }

        $string = filter_var($string, FILTER_SANITIZE_ENCODED);
        if (false === $string)
        {
            throw new UrlException('Invalid URL chunck!');
        }

        if ('' == trim($string))
        {
            throw new UrlException('Empty URL chunck!');
        }

        $this->urlAddons[] = $string;

        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return $this
     */
    public function addToQuery($key, $value)
    {
        $this->query[$key] = $value;

        return $this;
    }

    /**
     * Reset params and url addons
     * @return $this
     */
    public function reset()
    {
        $this->urlAddons = [];
        $this->query = [];

        return $this;
    }

    /**
     * @return string
     * @throws UrlException
     */
    public function compose()
    {
        $url = $this->baseUrl;
        if (is_array($this->urlAddons) && count($this->urlAddons) > 0)
        {
            $url .= self::URL_SEPARATOR . implode(self::URL_SEPARATOR, $this->urlAddons);
        }
        if (is_array($this->query) && count($this->query) > 0)
        {
            $url .= '?' . http_build_query($this->query);
        }

        return $url;
    }
}