<?php

namespace LaraCore\Tests\Unit\Routers;

use LaraCore\Framework\Routers\Router;
use LaraCore\Tests\TestCase;

class RouterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Reset static route table before each test
        $ref = new \ReflectionProperty(Router::class, 'routes');
        $ref->setAccessible(true);
        $ref->setValue(null, []);
    }

    public function testGetRegistersRoute(): void
    {
        Router::get('/home', fn() => 'home');
        $routes = $this->getRoutes();
        $this->assertCount(1, $routes);
        $this->assertSame('GET', $routes[0]['method']);
        $this->assertSame('/home', $routes[0]['uri']);
    }

    public function testPostRegistersRoute(): void
    {
        Router::post('/submit', fn() => 'ok');
        $routes = $this->getRoutes();
        $this->assertSame('POST', $routes[0]['method']);
    }

    public function testDeleteRegistersRoute(): void
    {
        Router::delete('/item', fn() => 'deleted');
        $routes = $this->getRoutes();
        $this->assertSame('DELETE', $routes[0]['method']);
    }

    public function testPutRegistersRoute(): void
    {
        Router::put('/item', fn() => 'updated');
        $routes = $this->getRoutes();
        $this->assertSame('PUT', $routes[0]['method']);
    }

    public function testNameAssignsNameToLastRoute(): void
    {
        Router::get('/dashboard', fn() => '')->name('dashboard');
        $routes = $this->getRoutes();
        $this->assertSame('dashboard', $routes[0]['name']);
    }

    public function testRouteHelperResolvesNamedRoute(): void
    {
        Router::get('/users/{id}', fn() => '')->name('user.show');
        $uri = Router::route('user.show', ['id' => 42]);
        $this->assertSame('/users/42', $uri);
    }

    public function testRouteHelperReturnsNullForUnknownName(): void
    {
        $this->assertNull(Router::route('nonexistent'));
    }

    public function testMultipleRoutesAccumulate(): void
    {
        Router::get('/a', fn() => '');
        Router::get('/b', fn() => '');
        Router::post('/c', fn() => '');
        $this->assertCount(3, $this->getRoutes());
    }

    public function testUnsupportedMethodThrowsException(): void
    {
        $this->expectException(\Exception::class);
        Router::patch('/x', fn() => '');
    }

    // ---- helpers ----

    private function getRoutes(): array
    {
        $ref = new \ReflectionProperty(Router::class, 'routes');
        $ref->setAccessible(true);
        return $ref->getValue(null);
    }
}
