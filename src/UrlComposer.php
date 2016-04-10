<?php

namespace UrlComposer;

use UrlComposer\Exceptions\UrlException;

class UrlComposer
{
    const SCHEME_HTTP    = 'http';
    const PATH_SEPARATOR = '/';

    /**
     * @var string
     */
    protected $scheme = self::SCHEME_HTTP;
    /**
     * @var string
     */
    protected $user = '';
    /**
     * @var string
     */
    protected $pass = '';
    /**
     * @var string
     */
    protected $host = '';
    /**
     * @var int
     */
    protected $port;
    /**
     * @var array
     */
    protected $path = [];
    /**
     * @var array
     */
    protected $query = [];
    /**
     * @var string
     */
    protected $fragment = '';

    /**
     * Url constructor.
     *
     * @param string $url
     *
     * @throws UrlException
     */
    public function __construct($url = '')
    {
        if ('' != $url && false == filter_var($url, FILTER_VALIDATE_URL))
        {
            throw new UrlException(sprintf('Invalid URL! (%s)', $url));
        }

        $this->parseUrlString($url);
    }

    /**
     * @param $url
     */
    protected function parseUrlString($url)
    {
        // Parse URL
        $scheme = parse_url($url, PHP_URL_SCHEME);
        if (!is_null($scheme))
        {
            $this->scheme = $scheme;
        }

        $user = parse_url($url, PHP_URL_USER);
        if (!is_null($user))
        {
            $this->user = $user;
        }

        $pass = parse_url($url, PHP_URL_PASS);
        if (!is_null($pass))
        {
            $this->pass = $pass;
        }

        $host = parse_url($url, PHP_URL_HOST);
        if (!is_null($host))
        {
            $this->host = idn_to_utf8($host);
        }

        $this->port = parse_url($url, PHP_URL_PORT);

        $pathStr = parse_url($url, PHP_URL_PATH);
        if (!is_null($pathStr) && self::PATH_SEPARATOR != $pathStr)
        {
            $this->path = explode(self::PATH_SEPARATOR, $pathStr);
        }

        $queryStr = parse_url($url, PHP_URL_QUERY);
        if (!is_null($queryStr))
        {
            parse_str($queryStr, $query);
            $this->query = $query;
        }

        $fragment = parse_url($url, PHP_URL_FRAGMENT);
        if (!is_null($fragment))
        {
            $this->fragment = $fragment;
        }
    }

    /**
     * @param string $string
     *
     * @return $this
     * @throws UrlException
     */
    public function addToPath($string)
    {
        if (!is_string($string))
        {
            throw new UrlException('Invalid path part! Path part must be an string.');
        }

        $string = filter_var($string, FILTER_SANITIZE_ENCODED);
        if (false === $string)
        {
            throw new UrlException('Invalid URL chunck!');
        }

        if ('' == trim($string))
        {
            throw new UrlException('Path part can not be empty!');
        }

        $this->path[] = $string;

        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return $this
     * @throws UrlException
     */
    public function addToQuery($key, $value)
    {
        if ('' == trim($key))
        {
            throw new UrlException('Query key can not be empty!');
        }
        $this->query[$key] = $value;

        return $this;
    }

    /**
     * @param $user
     * @param $pass
     *
     * @return $this
     */
    public function setUserInfo($user, $pass)
    {
        return $this->setUser($user)->setPass($pass);
    }

    /**
     * Reset auth, path, query & fragment
     * @return $this
     */
    public function reset()
    {
        $this->resetFragment();
        $this->resetPath();
        $this->resetQuery();
        $this->resetUserInfo();

        return $this;
    }

    /**
     * @return $this
     */
    public function resetFragment()
    {
        $this->fragment = null;

        return $this;
    }

    /**
     * @return $this
     */
    public function resetPath()
    {
        $this->path = [];

        return $this;
    }

    /**
     * @return $this
     */
    public function resetQuery()
    {
        $this->query = [];

        return $this;
    }

    /**
     * @return $this
     */
    public function resetUserInfo()
    {
        $this->user = null;
        $this->pass = null;

        return $this;
    }

    /**
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * @param string $scheme
     *
     * @return $this
     */
    public function setScheme($scheme)
    {
        $this->scheme = $scheme;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserInfo()
    {
        if (!empty($this->getPass()) && !empty($this->getUser()))
        {
            $userInfo = sprintf('%s:%s', $this->getUser(), $this->getPass());
        }
        elseif (empty($this->getPass()) && !empty($this->getUser()))
        {
            $userInfo = $this->getUser();
        }
        else
        {
            $userInfo = '';
        }

        return $userInfo;
    }

    /**
     * @return string
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * @param string $pass
     *
     * @return $this
     */
    public function setPass($pass)
    {
        $this->pass = $pass;

        return $this;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $user
     *
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $host
     *
     * @return $this
     */
    public function setHost($host)
    {
        $this->host = idn_to_utf8($host);

        return $this;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param int $port
     *
     * @return $this
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * @param bool $asString
     *
     * @return array|string
     */
    public function getPath($asString = false)
    {
        $path = $this->path;
        if (true == $asString)
        {
            $path = implode(self::PATH_SEPARATOR, $path);
        }

        return $path;
    }

    /**
     * @param array $path
     *
     * @return $this
     */
    public function setPath(array $path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @param bool $asString
     *
     * @return array|string
     */
    public function getQuery($asString = false)
    {
        $query = $this->query;
        if (true == $asString)
        {
            $query = http_build_query($query);
        }

        return $query;
    }

    /**
     * @param array $query Key-value array
     *
     * @return $this
     */
    public function setQuery(array $query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * @return string
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * @param string $fragment
     *
     * @return $this
     */
    public function setFragment($fragment)
    {
        $this->fragment = $fragment;

        return $this;
    }

    /**
     * @return string
     */
    protected function composeAuthority()
    {
        $userInfo = $this->getUserInfo();
        $port = $this->getPort();
        $host = $this->getHost();
        $authority = idn_to_ascii($host);
        if (!empty($userInfo))
        {
            $authority = sprintf('%s@%s', $userInfo, $authority);
        }
        if (!is_null($port))
        {
            $authority = sprintf('%s:%s', $authority, $port);
        }

        return $authority;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        try
        {
            $url = $this->compose();
        }
        catch (\Exception $e)
        {
            $url = $e->getMessage();
        }

        return $url;
    }

    /**
     * @return string
     * @throws UrlException
     */
    public function compose()
    {
        $url = sprintf('%s://%s', $this->getScheme(), $this->composeAuthority());
        if (!empty($this->getPath(true)))
        {
            $url = sprintf('%s/%s', $url, $this->getPath(true));
        }
        if (!empty($this->getQuery(true)))
        {
            $url = sprintf('%s?%s', $url, $this->getQuery(true));
        }
        if (!empty($this->getFragment()))
        {
            $url = sprintf('%s#%s', $url, $this->getFragment());
        }

        if (false == filter_var($url, FILTER_VALIDATE_URL))
        {
            throw new UrlException(sprintf('URL composition error! Please check your data. ' .
                                           'The composition result is an invalid URL: "%s"',
                                           $url));
        }

        return $url;
    }
}