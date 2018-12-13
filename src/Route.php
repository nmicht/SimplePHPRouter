<?php
/**
 * Simple PHP Router - A real simple PHP Router for PHP 7.1+
 *
 * @author      Michelle Torres <hola@michelletorres.mx>
 * @link        https://github.com/nmicht/SimplePHPRouter
 * @license     MIT
 */

namespace SimplePHPRouter;

use SimplePHPRouter\Validator;
use Exception;

/**
 * Route
 *
 * Class to hanlde the rules for each route.
 * Regex, path, controller, method and all the information related to the Route.
 */
class Route
{
    /**
     * URL of this Route
     * @var string
     */
    private $url = '';

    /**
     * Regex of this Route
     * @var string
     */
    private $regex = '';

    /**
     * Accepted HTTP method for this route.
     * @var string
     */
    private $method = '';

    /**
     * The callback method to execute when the route is matched
     * @var callable
     */
    private $callback;

    /**
     * Constructor
     *
     * @param callable $callback
     * @param string $url
     * @param string|array $method
     */
    public function __construct(
        string $url = null,
        callable $callback,
        string $method = null
    ) {
        $this->setUrl($url);
        $this->setCallback($callback);
        $this->setMethod($method);
        $this->compileRoute();
    }

    /**
     * Get the route callback.
     *
     * @return callable
     */
    public function getCallback() : callable
    {
        return $this->callback;
    }

    /**
     * Set the callback for the route.
     *
     * @param callable $callback
     * @return Route
     */
    public function setCallback(callable $callback = null) : Route
    {
        if (!is_callable($callback)) {
            throw new Exception('Expected a callable for Route. Got an uncallable '. gettype($callback));
        }
        $this->callback = $callback;

        return $this;
    }

    /**
     * Get the route url.
     *
     * @return string
     */
    public function getUrl() : string
    {
        return $this->url;
    }

    /**
     * Set the route url.
     *
     * @param string $url
     * @return Route
     */
    public function setUrl(string $url = null) : Route
    {
        $this->url = $url ?? '/';

        return $this;
    }

    /**
     * Get the route method.
     *
     * @return string
     */
    public function getMethod() : string
    {
        return $this->method;
    }

    /**
     * Set the route method.
     * Default set to 'GET'.
     *
     * @throws Exception when the method is not valid.
     * @param string $method
     * @return Route
     */
    public function setMethod(string $method = null) : Route
    {
        if ($method !== null && !is_string($method)) {
            // @TODO create a specific Exception
            throw new Exception('Expected an string as http method. Got a '. gettype($method));
        }

        if ($method !== null && !in_array(strtoupper($method), Validator::HTTP_METHODS)) {
            throw new Exception('The HTTP method is not allowed');
        }

        $this->method = strtoupper($method ?? 'GET');

        return $this;
    }

    /**
     * Get the route regex
     * .
     * @return string regex
     */
    public function getRegex() : string
    {
        return $this->regex;
    }

    /**
     * Compiles a route string to a regular expression
     *
     * @param string $route     The route string to compile
     * @return Route
     */
    protected function compileRoute() : Route
    {
        // Escape all of the non-named param
        $route = preg_replace_callback(
            Validator::ROUTE_ESCAPE_REGEX,
            function ($match) {
                return preg_quote($match[0]);
            },
            $this->url
        );

        // Get a local reference of the match types to pass into closure
        $matchTypes = Validator::MATCH_TYPES;

        // Compile the route url to get the regex
        $route = preg_replace_callback(
            Validator::ROUTE_COMPILE_REGEX,
            function ($match) use ($matchTypes) {
                list(, $pre, $type, $param, $optional) = $match;
                if (isset($matchTypes[$type])) {
                    $type = $matchTypes[$type];
                }
                // Older versions of PCRE require the 'P' in (?P<named>)
                $pattern = '(?:'
                         . ($pre !== '' ? $pre : null)
                         . '('
                         . ($param !== '' ? "?P<$param>" : null)
                         . $type
                         . '))'
                         . ($optional !== '' ? '?' : null);
                return $pattern;
            },
            $route
        );
        $this->regex = "`^$route$`";

        return $this;
    }

    /**
     * Callback execution.
     *
     * @return Response the return value of the callback
     * @throws Exception when there is not a callable
     */
    public function dispatch(Request $req = null)
    {
        if (!is_callable($this->callback)) {
            throw new Exception('Expected a callable for Route. Got an uncallable '. gettype($this->callback));
        }
        return call_user_func($this->callback, $req);
    }
}
