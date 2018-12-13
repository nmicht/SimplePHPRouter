<?php
/**
 * Simple PHP Router - A real simple PHP Router for PHP 7.1+
 *
 * @author      Michelle Torres <hola@michelletorres.mx>
 * @link        https://github.com/nmicht/SimplePHPRouter
 * @license     MIT
 */

namespace SimplePHPRouter;

use SimplePHPRouter\Request;

/**
 * Router
 *
 * The class to handle the functionality.
 */
class Router
{
    /**
     * @TODO change to use a DS collection
     * Array that holds all Route objects
     * @var array
     */
    private $routes = [];

    /**
     * The Request object
     * @var Request
     */
    public $request;

    /**
     * Constructor for router
     *
     * @param array $routeCollection A collection with all the routes.
     */
    public function __construct(array $routeCollection = [])
    {
        $this->routes = $routeCollection;
    }

    /**
     * Execute the validation of the current request with the current routes
     * collection.
     * @return Route
     */
    public function run() :? Route
    {
        $this->request = new Request();

        return $this->match();
    }

    /**
     * Add new route to the collection
     *
     * @param  string   $url
     * @param  callable $callback
     * @param  string   $method
     * @return Router
     */
    public function addRoute(string $url, callable $callback, string $method = null) : Router
    {
        $r = new Route($url, $callback, $method);
        $this->routes[] = $r;

        return $this;
    }

    /**
     * Get Routes
     * @return array
     */
    public function getRoutes() : array
    {
        return $this->routes;
    }

    /**
     * Preg validation for routing.
     *
     * @return Route
     */
    public function match() :? Route
    {
        // @TODO Change for substring validations to avoid regex as much as
        // possible. Regex is so expensive.
        foreach ($this->routes as $route) {
            if (!preg_match($route->getRegex(), $this->request->getPath(), $params)) {
                continue;
            }

            // Add match params into request params array.
            array_walk($params, function ($value, $key) {
                if (is_string($key)) {
                    $this->request->addParam($key, $value);
                }
            });

            $route->dispatch($this->request);

            return $route;
        }

        return null;
    }

    /**
     * Get the different routes in parts
     *
     * @return array
     * @throws \exception
     */
    private static function getRouteParts(): array
    {
        $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $url = parse_url($url);

        $urlParts = array_filter(explode('/', $url['path']));

        return $urlParts;
    }

    /**
     * Get the possible child routes based in the current url
     *
     * @param $homepageText
     * @return array
     * @throws \exception
     */
    public static function getChildRoutes(string $homepageText = 'Home'): array
    {
        $routes = [];
        $currentRoute = [];
        $urlParts = self::getRouteParts();
        unset($urlParts[0]);

        //Adding home route
        $routes[] = [
            'url'  => '/',
            'text' => $homepageText,
        ];

        // Build remaining routes
        foreach ($urlParts as $urlPart) {
            $currentRoute[] = $urlPart;
            $routes[] = [
                'url'  => '/' . implode('/', $currentRoute) . '/',
                'text' => ucfirst(str_replace('-', ' ', $urlPart)),
            ];
        }

        return $routes;
    }

    /**
     * Load routes from yaml file.
     *
     * @param  string $path
     * @return Router
     */
    public static function loadFromFile(string $path = 'routes.yaml') : Router
    {
        // @TODO add here all the logic to read the yaml
        $yamlRoutes = [];

        return new Router($yamlRoutes);
    }
}
