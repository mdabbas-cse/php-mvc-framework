<?php

namespace LaraCore\Tests\Unit;

use LaraCore\Framework\Request;
use LaraCore\Tests\TestCase;

class RequestTest extends TestCase
{
    private function makeRequest(string $uri, string $method = 'GET'): Request
    {
        $_SERVER['REQUEST_URI']    = $uri;
        $_SERVER['REQUEST_METHOD'] = $method;
        return new Request();
    }

    protected function tearDown(): void
    {
        unset($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
        parent::tearDown();
    }

    public function testUriReturnsSlashForRoot(): void
    {
        $req = $this->makeRequest('/');
        $this->assertSame('/', $req->uri());
    }

    public function testUriStripsLeadingSlash(): void
    {
        $req = $this->makeRequest('/users/profile');
        $this->assertSame('users/profile', $req->uri());
    }

    public function testUriStripsQueryString(): void
    {
        $req = $this->makeRequest('/search?q=hello');
        $this->assertSame('search', $req->uri());
    }

    public function testMethodReturnsGetUppercase(): void
    {
        $req = $this->makeRequest('/', 'GET');
        $this->assertSame('GET', $req->method());
    }

    public function testMethodReturnsPostUppercase(): void
    {
        $req = $this->makeRequest('/', 'POST');
        $this->assertSame('POST', $req->method());
    }

    public function testIsGetReturnsTrueForGet(): void
    {
        $req = $this->makeRequest('/', 'GET');
        $this->assertTrue($req->isGet());
        $this->assertFalse($req->isPost());
    }

    public function testIsPostReturnsTrueForPost(): void
    {
        $req = $this->makeRequest('/', 'POST');
        $this->assertTrue($req->isPost());
        $this->assertFalse($req->isGet());
    }

    public function testIsPutReturnsTrueForPut(): void
    {
        $req = $this->makeRequest('/', 'PUT');
        $this->assertTrue($req->isPut());
    }

    public function testIsDeleteReturnsTrueForDelete(): void
    {
        $req = $this->makeRequest('/', 'DELETE');
        $this->assertTrue($req->isDelete());
    }

    public function testSetRouteParamsAndGetParam(): void
    {
        $req = $this->makeRequest('/users/42');
        $req->setRouteParams(['id' => '42']);
        $this->assertSame('42', $req->getParam('id'));
    }

    public function testGetRouteParamsReturnsAllParams(): void
    {
        $req = $this->makeRequest('/posts/5/comments/3');
        $req->setRouteParams(['postId' => '5', 'commentId' => '3']);
        $this->assertSame(['postId' => '5', 'commentId' => '3'], $req->getRouteParams());
    }

    public function testSetRouteParamsReturnsSelf(): void
    {
        $req = $this->makeRequest('/');
        $result = $req->setRouteParams(['x' => '1']);
        $this->assertSame($req, $result);
    }
}
