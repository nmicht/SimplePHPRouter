<?php
namespace SimplePHPRouterTest\Test;

use PHPUnit\Framework\TestCase;
use SimplePHPRouter\Route;

final class RouteTest extends TestCase
{
    private $routeWithParameters;
    private $url;

    protected function setUp()
    {
        $this->url = '/news/[s:slug]';
        $this->routeWithParameters = new Route(
            $this->url,
            [\SimplePHPRouterTest\TestController::class, 'page']
        );
    }

    public function testGetUrl()
    {
        self::assertEquals($this->url, $this->routeWithParameters->getUrl());
    }

    public function testSetUrlWithParams()
    {
        $this->routeWithParameters->setUrl($this->url);
        self::assertEquals($this->url, $this->routeWithParameters->getUrl());
    }

    public function testSetUrlNull()
    {
        $this->routeWithParameters->setUrl();
        self::assertEquals('/', $this->routeWithParameters->getUrl());
    }

    public function testSetUrlRoot()
    {
        $this->routeWithParameters->setUrl('/');
        self::assertEquals('/', $this->routeWithParameters->getUrl());
    }

    // public function testGetMethods()
    // {
    //     self::assertEquals(array('GET'), $this->routeWithParameters->getMethods());
    // }
    //
    // public function testSetMethods()
    // {
    //     $this->routeWithParameters->setMethods(array('POST'));
    //     self::assertEquals(array('POST'), $this->routeWithParameters->getMethods());
    //     $this->routeWithParameters->setMethods(array('GET', 'POST', 'PUT', 'DELETE'));
    //     self::assertEquals(array('GET', 'POST', 'PUT', 'DELETE'), $this->routeWithParameters->getMethods());
    // }
    //
    // public function testGetTarget()
    // {
    //     self::assertEquals('thisIsAString', $this->routeWithParameters->getTarget());
    // }
    //
    // public function testSetTarget()
    // {
    //     $this->routeWithParameters->setTarget('ThisIsAnotherString');
    //     self::assertEquals('ThisIsAnotherString', $this->routeWithParameters->getTarget());
    // }
    //
    // public function testGetName()
    // {
    //     self::assertEquals('page', $this->routeWithParameters->getName());
    // }
    //
    // public function testSetName()
    // {
    //     $this->routeWithParameters->setName('pageroute');
    //     self::assertEquals('pageroute', $this->routeWithParameters->getName());
    // }
    //
    // public function testGetAction()
    // {
    //     self::assertEquals('page', $this->routeWithParameters->getAction());
    // }
}
