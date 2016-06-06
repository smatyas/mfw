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

use Smatyas\Mfw\Container\Container;
use Smatyas\Mfw\Http\Request;
use Smatyas\Mfw\Router\Route;

class RouteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the default values and that the accessor methods work as expected.
     */
    public function testDefaultsAndAccessors()
    {
        $route = new Route();
        $this->assertNull($route->getPath());
        $this->assertSame('GET', $route->getMethod());
        $this->assertNull($route->getController());
        $this->assertSame('indexAction', $route->getControllerAction());

        $route->setPath('/test');
        $this->assertSame('/test', $route->getPath());

        $route->setMethod('POST');
        $this->assertSame('POST', $route->getMethod());

        $route->setController('\\stdClass');
        $this->assertSame('\\stdClass', $route->getController());

        $route->setControllerAction('test2');
        $this->assertSame('test2Action', $route->getControllerAction());
    }

    /**
     * Tests that the handle method for missing controllers throws the expected exception.
     *
     * @param $controller
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessageRegExp /^Missing controller class:/
     *
     * @dataProvider missingControllerDataProvider
     */
    public function testHandleMissingController($controller)
    {
        $route = new Route();
        $route->setController($controller);
        $container = new Container();
        $testRequest = new Request();
        $testRequest->setUri('/test');
        $testRequest->setMethod('GET');

        $this->assertNull($route->handle($testRequest, $container));
    }

    /**
     * Provides test data for the testHandleMissingController test.
     */
    public function missingControllerDataProvider()
    {
        return [
            [null],
            ['\\MissingNamespace\\Path\\Class'],
        ];
    }

    /**
     * Tests that the handle method for wrong controllers throws the expected exception.
     *
     * @param $controller
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessageRegExp /^The ".*" controller must implement the ControllerInterface interface.$/
     *
     * @dataProvider handleControllerInterfaceCheckDataProvider
     */
    public function testHandleControllerInterfaceCheck($controller)
    {
        $route = new Route();
        $route->setController($controller);
        $container = new Container();
        $testRequest = new Request();
        $testRequest->setUri('/test');
        $testRequest->setMethod('GET');

        $this->assertNull($route->handle($testRequest, $container));
    }

    /**
     * Provides test data for the testHandleControllerInterfaceCheck test.
     */
    public function handleControllerInterfaceCheckDataProvider()
    {
        return [
            ['\\stdClass'],
            ['\\DateTime'],
        ];
    }
}
