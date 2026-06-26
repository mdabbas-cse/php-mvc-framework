<?php

namespace LaraCore\Tests\Feature\Http\Controllers;

use LaraCore\App\Http\Controllers\HomeController;
use LaraCore\Framework\Request;
use LaraCore\Framework\Response;
use LaraCore\Tests\TestCase;

class HomeControllerTest extends TestCase
{
    private function makeRequest(string $uri = '/', string $method = 'GET'): Request
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

    public function testHomeControllerIsInstantiable(): void
    {
        $controller = new HomeController();
        $this->assertInstanceOf(HomeController::class, $controller);
    }

    public function testIndexMethodExists(): void
    {
        $controller = new HomeController();
        $this->assertTrue(method_exists($controller, 'index'));
    }

    public function testListMethodExists(): void
    {
        $controller = new HomeController();
        $this->assertTrue(method_exists($controller, 'list'));
    }

    public function testIndexIsCallable(): void
    {
        $this->assertTrue(is_callable([new HomeController(), 'index']));
    }

    public function testListIsCallable(): void
    {
        $this->assertTrue(is_callable([new HomeController(), 'list']));
    }
}
