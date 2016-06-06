<?php
/**
 * This file is part of the mfw package.
 *
 * (c) Mátyás Somfai <somfai.matyas@gmail.com>
 * Created at 2016.06.06.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Smatyas\Mfw\Tests\Router;

use Smatyas\Mfw\Http\Request;
use Smatyas\Mfw\Router\Route;
use Smatyas\Mfw\Router\Router;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests that the addRoute and getRoutes methods perform as expected.
     */
    public function testAddAndGet()
    {
        $router = new Router();
        $this->assertCount(0, $router->getRoutes());
        $this->assertSame([], $router->getRoutes());

        $route1 = new Route();
        $route1->setPath('/test-route-1');
        $route1->setController('\\Smatyas\\Mfw\\Tests\\TestController');
        $route1->setControllerAction('test');
        $route1->setMethod('GET');
        $router->addRoute($route1);
        $this->assertCount(1, $router->getRoutes());
        $this->assertSame([$route1], $router->getRoutes());

        $route2 = new Route();
        $route2->setPath('/test-route-2');
        $route2->setController('\\Smatyas\\Mfw\\Tests\\TestController');
        $route2->setControllerAction('test');
        $route2->setMethod('GET');
        $router->addRoute($route2);
        $this->assertCount(2, $router->getRoutes());
        $this->assertSame([$route1, $route2], $router->getRoutes());
    }

    /**
     * Tests that if there is no matching route, then null is returned.
     */
    public function testMatchNoRoutes()
    {
        $router = new Router();
        $testRequest = new Request();
        $testRequest->setUri('/test');
        $testRequest->setMethod('GET');

        // There is no route in the router.
        $this->assertNull($router->match($testRequest));

        // Adding one route, but with a different path then the test request.
        $route1 = new Route();
        $route1->setPath('/test-route-1');
        $route1->setController('\\Smatyas\\Mfw\\Tests\\TestController');
        $route1->setControllerAction('test');
        $route1->setMethod('GET');
        $router->addRoute($route1);
        $this->assertNull($router->match($testRequest));

        // Adding another route that matches the request.
        $route2 = new Route();
        $route2->setPath('/test');
        $route2->setController('\\Smatyas\\Mfw\\Tests\\TestController');
        $route2->setControllerAction('test');
        $route2->setMethod('GET');
        $router->addRoute($route2);
        $this->assertSame($route2, $router->match($testRequest));
    }
}
