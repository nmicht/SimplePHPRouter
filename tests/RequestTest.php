<?php
namespace SimplePHPRouterTest\Test;

use PHPUnit\Framework\TestCase;
use SimplePHPRouter\Request;

final class RequestTest extends TestCase
{
    protected $requestGet;
    protected $requestPost;
    protected $get;
    protected $post;

    protected function setUp()
    {
        //Buil get request
        $_GET = [
            'a' => 'x',
            'y' => 'asdf',
            'j' => 'true',
            'something' => '5',
            'article' => '',
        ];
        $this->get = $_GET;
        $_SERVER = [
            'REQUEST_URI' => '/news/2019-acura-rdx-headed-new-york/?a=x&y=asdf&j=true&something=5&article',
            'REQUEST_METHOD' => 'GET',
            'PATH_INFO' => '/news/2019-acura-rdx-headed-new-york/',
            'QUERY_STRING' => 'a=x&y=asdf&j=true&something=5&article',
        ];

        $this->requestGet = new Request();

        //Build post request
        $_POST = [
        ];
        $this->post = $_POST;
        $_SERVER = [
            'REQUEST_URI' => '/news/2019-acura-rdx-headed-new-york/?a=x&y=asdf&j=true&something=5&article',
            'REQUEST_METHOD' => 'POST',
            'PATH_INFO' => '/news/2019-acura-rdx-headed-new-york/',
            'QUERY_STRING' => 'a=x&y=asdf&j=true&something=5&article',
        ];

        $this->requestPost = new Request();
    }

    public function testGetMethodGet()
    {
        self::assertEquals('GET', $this->requestGet->getMethod());
    }

    public function testGetMethodPost()
    {
        self::assertEquals('POST', $this->requestPost->getMethod());
    }

    public function testGetPathWithoutLastSlash()
    {
        self::assertEquals('/news/2019-acura-rdx-headed-new-york', $this->requestGet->getPath());
        self::assertEquals('/news/2019-acura-rdx-headed-new-york', $this->requestPost->getPath());
    }

    public function testGetParamsIsArray()
    {
        self::assertTrue(is_array($this->requestGet->getParams()));
        self::assertTrue(is_array($this->requestPost->getParams()));
    }

    public function testGetParamsGet()
    {
        self::assertArraySubset($this->get, $this->requestGet->getParams());
    }

    public function testGetParamsPost()
    {
        self::assertArraySubset($this->post, $this->requestPost->getParams());
    }

    public function testSetParamNull()
    {
        $key = 'testing';
        $value = null;
        $subset = [$key => $value];

        $this->requestGet->addParam($key, $value);
        self::assertArraySubset($subset, $this->requestGet->getParams());
    }

    public function testSetParamEmpty()
    {
        $key = 'testing';
        $value = '';
        $subset = [$key => $value];

        $this->requestGet->addParam($key, $value);
        self::assertArraySubset($subset, $this->requestGet->getParams());
    }

    public function testSetParamOverrideNotAllowed()
    {
        $key = 'a';
        $value = 'change';
        $subset = [$key => $value];

        self::expectException('Exception');
        $this->requestGet->addParam($key, $value);
    }

    public function testSetParamOverrideAllowed()
    {
        $key = 'a';
        $value = 'change';
        $subset = [$key => $value];

        $this->requestGet->addParam($key, $value, true);
        self::assertArraySubset($subset, $this->requestGet->getParams());
    }
}
