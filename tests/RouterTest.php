<?php
namespace SimplePHPRouterTest\Test;

use PHPUnit\Framework\TestCase;
use SimplePHPRouter\Router;
use SimplePHPRouter\Route;
use SimplePHPRouter\Request;

final class RouterTest extends TestCase
{
    protected $router;
    protected $route_url;
    protected $route_callback;
    protected $route;
    protected $request;

    protected function setUp()
    {
        $this->route_url = '/news/[s:slug]';
        $this->route_callback = [\SimplePHPRouterTest\TestController::class, 'page'];
        $this->route = new Route($this->route_url, $this->route_callback);
        $this->router = new Router();

        $_GET = [
            'a' => 'x',
            'y' => 'asdf',
            'j' => 'true',
            'something' => '5',
            'article' => '',
        ];
        $_SERVER = [
            'REQUEST_URI' => '/news/2019-acura-rdx-headed-new-york/?a=x&y=asdf&j=true&something=5&article',
            'REQUEST_METHOD' => 'GET',
            'PATH_INFO' => '/news/2019-acura-rdx-headed-new-york/',
            'QUERY_STRING' => 'a=x&y=asdf&j=true&something=5&article',
        ];

        $this->router->request = new Request();
    }

    public function testRouterFromFileReturnsRouter()
    {
        self::assertInstanceOf(Router::class, Router::loadFromFile());
    }

    public function testGetRoutesFromFileMatch()
    {
        $routes = []; //routes array
        $router = Router::loadFromFile();

        self::assertCount(count($routes), $router->getRoutes());
        self::assertArraySubset($routes, $router->getRoutes());
    }

    public function testConstructRoutes()
    {
        $routes = []; //routes array
        $router = new Router($routes);

        self::assertCount(count($routes), $router->getRoutes());
        self::assertArraySubset($routes, $router->getRoutes());
    }

    public function testAddRouteToArray()
    {
        $this->router->addRoute($this->route_url, $this->route_callback);

        self::assertArraySubset([$this->route], $this->router->getRoutes());
    }

    public function testGetRoutesReturnArrayWithRouteObjects()
    {
        $routes = $this->router->getRoutes();

        self::assertThat($routes, self::isType('array'));

        foreach ($routes as $route) {
            self::assertThat($route, self::isInstanceOf(Router::class));
        }
    }

    public function testMatchReturnRoute()
    {
        $this->router->addRoute($this->route_url, $this->route_callback);
        self::assertEquals($this->route, $this->router->match());
    }

    public function testMatchOnFailReturnNull()
    {
        $this->router->addRoute('fail', $this->route_callback);
        self::assertEquals(null, $this->router->match());
    }

    public function testQueryParamsAddedToRequest()
    {
        $this->router->addRoute($this->route_url, $this->route_callback);
        $this->router->run();
        self::assertArrayHasKey('slug', $this->router->request->getParams());
    }

    public function testRequestAccesibleOnCallback()
    {
        self::assertEquals($this->request, $this->router->run());
    }
}
