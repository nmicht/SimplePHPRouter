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
     * The requested url.
     *
     * @var string
     */
    private $url = '';

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
     * Constructor
     * Initialize the request object using the own setters to validate.
     */
    public function __construct()
    {
        $this->setMethod()
             ->setUrl()
             ->setQueryString()
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
     * Get the requested url.
     *
     * @return string
     */
    public function getUrl() : string
    {
        return $this->url;
    }

    /**
     * Set the requested url.
     *
     * @return Request
     */
    private function setUrl() : Request
    {
        $this->url = $_SERVER['REQUEST_URI'];

        if (($pos = strpos($this->url, '?')) !== false) {
            $this->url = substr($this->url, 0, $pos);
        }

        if ($this->url !== '/') {
            $this->url = rtrim($this->url, '/');
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
        if (($pos = strpos($_SERVER['REQUEST_URI'], '?')) !== false) {
            $this->queryString = substr($_SERVER['REQUEST_URI'], $pos + 1);
        }

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
     * Set the requested params from queryString in array with key-value.
     * @todo include params from post and body
     *
     * @return Request
     */
    private function setParams() : Request
    {
        if ($this->queryString) {
            $queries = explode('&', $this->queryString);
            foreach ($queries as $query) {
                $key_value = explode('=', $query);
                $this->addParam($key_value[0], $key_value[1] ?? null);
            }
        }

        return $this;
    }

    /**
     * Add new query param into params array.
     *
     * @param  string  $key
     * @param  string  $value
     * @param  boolean $force Define if the addition must be forced replacing
     * previous param.
     *
     * @return Request
     */
    public function addParam(string $key, string $value = null, bool $force = false) : Request
    {
        if (!$force && isset($this->params[$key])) {
            // @TODO create specific exception
            throw new Exception("Param already defined", 1);
        }
        $this->params[$key] = $value ?? '';

        return $this;
    }
}
