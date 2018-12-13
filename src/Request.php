<?php
/**
 * Simple PHP Router - A real simple PHP Router for PHP 7.1+
 *
 * @author      Michelle Torres <hola@michelletorres.mx>
 * @link        https://github.com/nmicht/SimplePHPRouter
 * @license     MIT
 */

namespace SimplePHPRouter;

use Exception;

/**
 * Request
 *
 * Class to hanlde the HTTP request information.
 */
class Request
{
    /**
     * The request http method.
     *
     * @var string
     */
    private $method = '';

    /**
     * The requested path.
     *
     * @var string
     */
    private $path = '';

    /**
     * The request query string.
     *
     * @var string
     */
    private $queryString = '';

    /**
     * An array with the query string processed by key value.
     *
     * @var array
     */
    private $params = [];

    /**
     * The requested host
     *
     * @var string
     */
    private $host = '';

    /**
     * The requested protocol
     *
     * @var string
     */
    private $protocol = '';

    /**
     * The requested url
     *
     * @var string
     */
    private $url = '';

    /**
     * The requested subdomain
     *
     * @var string
     */
    private $subdomain = '';

    /**
     * Constructor
     * Initialize the request object using the own setters to validate.
     */
    public function __construct()
    {
        $this->setMethod()
             ->setPath()
             ->setQueryString()
             ->setHost()
             ->setProtocol()
             ->setSubdomain()
             ->setParams();
    }

    /**
     * Get the request method.
     *
     * @return string method.
     */
    public function getMethod() : string
    {
        return $this->method;
    }

    /**
     * Set the request method using the server and post data.
     *
     * @return Request
     */
    private function setMethod() : Request
    {
        $this->method = strtoupper($_POST['_method'] ?? $_SERVER['REQUEST_METHOD']);

        return $this;
    }

    /**
     * Get the requested path.
     *
     * @return string
     */
    public function getPath() : string
    {
        return $this->path;
    }

    /**
     * Set the requested path.
     *
     * @return Request
     */
    private function setPath() : Request
    {
        $path = parse_url($_SERVER['REQUEST_URI']);
        $this->path = $path['path'];

        // Remove trailing slash on paths
        if ($this->path !== '/') {
            $this->path = rtrim($this->path, '/');
        }

        return $this;
    }

    /**
     * Set the requested query string
     *
     * @return Request
     */
    private function setQueryString() : Request
    {
        $this->queryString = $_SERVER['QUERY_STRING'] ?? null;

        return $this;
    }

    /**
     * Get the array with requested params.
     *
     * @return array
     */
    public function getParams() : array
    {
        return $this->params;
    }

    /**
     * Set the requested host
     *
     * @return Request
     */
    private function setHost() : Request
    {
        $this->host = $_SERVER['HTTP_HOST'];
        return $this;
    }

    /**
     * Get the requested host.
     *
     * @return string
     */
    public function getHost() : string
    {
        return $this->host;
    }

    /**
     * Set the requested protocol
     *
     * @return Request
     */
    private function setProtocol() : Request
    {
        if ((! empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https') ||
            (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ||
            (! empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443')
        ) {
            $this->protocol = 'https';
        } else {
            $this->protocol = 'http';
        }

        return $this;
    }

    /**
     * Get the requested protocol.
     *
     * @return string
     */
    public function getProtocol() : string
    {
        return $this->protocol;
    }

    /**
     * Set the requested subdomain
     *
     * @return Request
     */
    private function setSubdomain() : Request
    {
        $parts = explode('.', $this->host);

        // Remove host
        $parts = array_slice($parts, 0, count($parts) - 2);

        // Remove www
        if (current($parts) === 'www') {
            array_shift($parts);
        }

        $this->subdomain = implode('.', $parts);

        return $this;
    }

    /**
     * Get the requested subdomain.
     *
     * @return string
     */
    public function getSubdomain() : string
    {
        return $this->subdomain;
    }

    /**
     * Get the requested url.
     *
     * @return string
     */
    public function getUrl() : string
    {
        return $this->protocol . '://' . $this->host . $this->path;
    }

    /**
     * Set the requested params from post and get..
     *
     * @return Request
     */
    private function setParams() : Request
    {
        $request = array_merge(
            (filter_input_array(INPUT_GET)  ?? []),
            (filter_input_array(INPUT_POST) ?? [])
        );

        foreach ($request as $key => $value) {
            $this->addParam($key, ($value ?? null), true);
        }

        return $this;
    }

    /**
     * Add new query param into params array.
     *
     * @param  string  $key
     * @param  string  $value
     * @param  boolean $force Define if the addition must be forced overiding
     * previous param.
     *
     * @return Request
     */
    public function addParam(string $key, string $value = null, bool $force = false) : Request
    {
        if (!$force && array_key_exists($key, $this->params)) {
            // @TODO create specific exception
            throw new Exception("Param already defined", 1);
        }

        $this->params[$key] = $value ?? '';

        return $this;
    }
}
