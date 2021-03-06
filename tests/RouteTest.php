<?php
namespace SimplePHPRouterTest\Test;

use PHPUnit\Framework\TestCase;
use SimplePHPRouter\Route;

final class RouteTest extends TestCase
{
    private $routeWithParameters;
    private $url;
    private $method;

    protected function setUp()
    {
        $this->url = '/news/[s:slug]';
        $this->method = 'GET';
        $this->regex = '`^/news(?:/(?P<slug>[0-9A-Za-z-_\-]++))$`';

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

    public function testGetMethod()
    {
        self::assertEquals($this->method, $this->routeWithParameters->getMethod());
    }

    public function testSetMethodDefault()
    {
        $this->routeWithParameters->setMethod();
        self::assertEquals('GET', $this->routeWithParameters->getMethod());
    }

    public function testSetMethodPost()
    {
        $this->routeWithParameters->setMethod('post');
        self::assertEquals('POST', $this->routeWithParameters->getMethod());
    }

    public function testSetMethodInvalid()
    {
        self::expectException('Exception');
        $this->routeWithParameters->setMethod('fail');
    }

    public function testGetRegex()
    {
        self::assertEquals($this->regex, $this->routeWithParameters->getRegex());
    }
}
