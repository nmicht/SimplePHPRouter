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
     * Constructor
     * Initialize the request object using the own setters to validate.
     */
    public function __construct()
    {
        $this->setMethod()
             ->setPath()
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
        $this->queryString = $_SERVER['QUERY_STRING'];

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
     * Set the requested params from post and get..
     *
     * @return Request
     */
    private function setParams() : Request
    {
        $get = filter_input_array(INPUT_GET);
        foreach ($_GET as $key => $value) {
            $this->addParam($key, $value ?? null, true);
        }

        $post = filter_input_array(INPUT_POST);
        foreach ($_POST as $key => $value) {
            $this->addParam($key, $value ?? null, true);
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
