<?php
namespace SimplePHPRouter;

use SimplePHPRouter\Request;

class Router
{
    /**
     * @TODO change to use a DS collection
     * Array that holds all Route objects
     * @var array
     */
    private $routes = [];

    /**
     * @TODO change to use a DS collection
     * Array to store named routes in, used for reverse routing.
     * @var array
     */
    private $namedRoutes = [];

    public $request;

    /**
     * Constructor for router
     *
     * @param array $routeCollection A collection with all the routes.
     */
    public function __construct(array $routeCollection = [])
    {
        $this->routes = $routeCollection;
        $this->distributeRoutes();
    }

    /**
     * Distribute routes to have the named routes in a different collection.
     * That collection is used to reverse routing.
     *
     * @return void
     */
    private function distributeRoutes() : void
    {
        foreach ($this->routes as $route) {
            $name = $route->getName();
            if (null !== $name) {
                $this->namedRoutes[$name] = $route;
            }
        }
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
     * @param  string   $url
     * @param  callable $callback
     * @param  string   $name
     * @param  string   $method
     * @return Router
     */
    public function addRoute(string $url, callable $callback, string $name = null, string $method = null) : Router
    {
        $r = new Route($callback, $url, $method, $name);
        $this->routes[] = $r;

        return $this;
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
            if (!preg_match($route->getRegex(), $this->request->getUrl(), $params)) {
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

    public static function loadFromFile(string $path = 'routes.yaml') : Router
    {
        // @TODO add here all the logic to read the yaml
        $yamlRoutes = [];

        return new Router($yamlRoutes);
    }
}
