<?php
/**
 * This file is part of the mfw package.
 *
 * (c) Mátyás Somfai <somfai.matyas@gmail.com>
 * Created at 2016.06.08.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Smatyas\Mfw\Tests;

use Smatyas\Mfw\Application;
use Smatyas\Mfw\ErrorHandler\EmailErrorHandler;
use Smatyas\Mfw\Http\Exception\NotFoundHttpException;
use Smatyas\Mfw\Http\Exception\UnauthorizedHttpException;
use Smatyas\Mfw\Http\RedirectResponse;
use Smatyas\Mfw\Http\Response;
use Smatyas\Mfw\Router\Route;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the application constructor.
     *
     * @param $config
     * @param $expectedException
     * @param $expectedExceptionMessage
     *
     * @dataProvider constructDataProvider
     */
    public function testConstruct($config, $expectedException, $expectedExceptionMessage)
    {
        if (null !== $expectedException) {
            $this->expectException($expectedException);
        }
        if (null !== $expectedExceptionMessage) {
            $this->expectExceptionMessage($expectedExceptionMessage);
        }

        $app = new Application($config);
        $this->assertTrue(is_array($app->getConfig()));

        if (null === $expectedException) {
            $appConfig = $app->getConfig();
            $this->assertSame($config['app_base_path'], $appConfig['app_base_path']);
            $this->assertSame($config['app_base_path'], $app->getAppBasePath());
            $this->assertSame($config['orm.config'], $appConfig['orm.config']);
            $this->assertArrayHasKey('routing', $appConfig);
            $this->assertArrayHasKey('templating', $appConfig);

            if (isset($config['orm.config'])) {
                $this->assertArrayHasKey('orm.manager', $appConfig);
            }

            if (isset($config['error_handler.config']['type']) && $config['error_handler.config']['type'] === 'email') {
                $this->assertInstanceOf(EmailErrorHandler::class, $app->get('error_handler'));
            }
        }
    }

    /**
     * Provides test data for the testConstruct tests.
     */
    public function constructDataProvider()
    {
        return [
            [
                [
                    'app_base_path' => __DIR__ . '/test_app',
                    'orm.config' => [
                        'type' => 'mfw',
                        'host' => 'db',
                        'database' => 'mfw',
                        'username' => 'mfw',
                        'password' => 'mfw',
                        'mapping' => [
                            'test' => 'Smatyas\\Mfw\\Tests\\Orm\\TestEntity',
                        ],
                    ],
                    'security.config' => [],
                    'error_handler.config' => [
                        'type' => 'email',
                        'to' => 'ops@example.com',
                    ],
                ],
                null,
                null,
            ],
            [
                [
                    'orm.config' => [
                        'type' => 'mfw',
                        'host' => 'db',
                        'database' => 'mfw',
                        'username' => 'mfw',
                        'password' => 'mfw',
                        'mapping' => [
                            'test' => 'Smatyas\\Mfw\\Tests\\Orm\\TestEntity',
                        ],
                    ],
                    'security.config' => [],
                ],
                '\\RuntimeException',
                'Mandatory application config parameter missing: app_base_path',
            ],
            [
                [
                    'app_base_path' => __DIR__ . '/test_app',
                    'routing' => new \stdClass(),
                    'orm.config' => [
                        'type' => 'mfw',
                        'host' => 'db',
                        'database' => 'mfw',
                        'username' => 'mfw',
                        'password' => 'mfw',
                        'mapping' => [
                            'test' => 'Smatyas\\Mfw\\Tests\\Orm\\TestEntity',
                        ],
                    ],
                    'security.config' => [],
                ],
                '\\RuntimeException',
                'The "routing" service must implement the RouterInterface',
            ],
            [
                [
                    'app_base_path' => __DIR__ . '/test_app',
                    'templating' => new \stdClass(),
                    'orm.config' => [
                        'type' => 'mfw',
                        'host' => 'db',
                        'database' => 'mfw',
                        'username' => 'mfw',
                        'password' => 'mfw',
                        'mapping' => [
                            'test' => 'Smatyas\\Mfw\\Tests\\Orm\\TestEntity',
                        ],
                    ],
                    'security.config' => [],
                ],
                '\\RuntimeException',
                'The "templating" service must implement the TemplatingInterface',
            ],
            [
                [
                    'app_base_path' => __DIR__ . '/test_app',
                    'security.checker' => new \stdClass(),
                    'orm.config' => [
                        'type' => 'mfw',
                        'host' => 'db',
                        'database' => 'mfw',
                        'username' => 'mfw',
                        'password' => 'mfw',
                        'mapping' => [
                            'test' => 'Smatyas\\Mfw\\Tests\\Orm\\TestEntity',
                        ],
                    ],
                    'security.config' => [],
                ],
                '\\RuntimeException',
                'The "security.checker" service must implement the SecurityCheckerInterface',
            ],
            [
                [
                    'app_base_path' => __DIR__ . '/test_app',
                    'orm.config' => [
                        'type' => 'mfw',
                        'host' => 'db',
                        'database' => 'mfw',
                        'username' => 'mfw',
                        'password' => 'mfw',
                        'mapping' => [
                            'test' => 'Smatyas\\Mfw\\Tests\\Orm\\TestEntity',
                        ],
                    ],
                ],
                '\\RuntimeException',
                'The "security.config" application parameter is missing.',
            ],
            [
                [
                    'app_base_path' => __DIR__ . '/test_app',
                    'orm.config' => [
                        'host' => 'db',
                        'database' => 'mfw',
                        'username' => 'mfw',
                        'password' => 'mfw',
                        'mapping' => [
                            'test' => 'Smatyas\\Mfw\\Tests\\Orm\\TestEntity',
                        ],
                    ],
                    'security.config' => [],
                ],
                '\\RuntimeException',
                'The "orm.config.type" must be set.',
            ],
            [
                [
                    'app_base_path' => __DIR__ . '/test_app',
                    'orm.config' => [
                        'type' => 'something-unknown',
                        'host' => 'db',
                        'database' => 'mfw',
                        'username' => 'mfw',
                        'password' => 'mfw',
                        'mapping' => [
                            'test' => 'Smatyas\\Mfw\\Tests\\Orm\\TestEntity',
                        ],
                    ],
                    'security.config' => [],
                ],
                '\\RuntimeException',
                'Unknown orm type: something-unknown',
            ],
        ];
    }

    /**
     * Tests that for a configured and accessible route, the correct methods are called.
     */
    public function testRun()
    {
        $config = [
            'app_base_path' => __DIR__ . '/test_app',
            'security.config' => [],
        ];

        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $mockedRoute = $this->createMock(Route::class);
        $mockedResponse = $this->createMock(Response::class);

        $app = $this->getMockBuilder(Application::class)
            ->setConstructorArgs([$config])
            ->setMethods(['sessionStart', 'getRoute', 'createResponse', 'sendResponse'])
            ->getMock();
        $app->expects($this->once())
            ->method('getRoute')
            ->willReturn($mockedRoute);
        $app->expects($this->once())
            ->method('createResponse')
            ->with($this->identicalTo($mockedRoute))
            ->willReturn($mockedResponse);
        $app->expects($this->once())
            ->method('sendResponse');

        $app->run();
    }

    /**
     * Tests that for an invalid route, the correct methods are called.
     */
    public function testRunWithNotFoundHttpException()
    {
        $config = [
            'app_base_path' => __DIR__ . '/test_app',
            'security.config' => [],
        ];

        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $app = $this->getMockBuilder(Application::class)
            ->setConstructorArgs([$config])
            ->setMethods(['sessionStart', 'getRoute', 'createResponse', 'sendResponse'])
            ->getMock();
        $app->expects($this->once())
            ->method('getRoute')
            ->willThrowException(new NotFoundHttpException());
        $app->expects($this->never())
            ->method('createResponse');
        $app->expects($this->once())
            ->method('sendResponse');

        $app->run();
    }

    /**
     * Tests that for an unauthorized route, the correct methods are called.
     */
    public function testRunWithUnauthorizedHttpException()
    {
        $config = [
            'app_base_path' => __DIR__ . '/test_app',
            'security.config' => [],
        ];

        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $app = $this->getMockBuilder(Application::class)
            ->setConstructorArgs([$config])
            ->setMethods(['sessionStart', 'getRoute', 'createResponse', 'sendResponse'])
            ->getMock();
        $app->expects($this->once())
            ->method('getRoute')
            ->willThrowException(new UnauthorizedHttpException());
        $app->expects($this->never())
            ->method('createResponse');
        $app->expects($this->once())
            ->method('sendResponse');

        $app->run();
    }

    /**
     * Tests that for an unauthorized route with a redirect response, the correct methods are called.
     */
    public function testRunWithUnauthorizedHttpExceptionProvidingResponse()
    {
        $config = [
            'app_base_path' => __DIR__ . '/test_app',
            'security.config' => [],
        ];

        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $exception = new UnauthorizedHttpException();
        $exception->setResponse(new RedirectResponse('/redirect'));

        $app = $this->getMockBuilder(Application::class)
            ->setConstructorArgs([$config])
            ->setMethods(['sessionStart', 'getRoute', 'createResponse', 'sendResponse'])
            ->getMock();
        $app->expects($this->once())
            ->method('getRoute')
            ->willThrowException($exception);
        $app->expects($this->never())
            ->method('createResponse');
        $app->expects($this->once())
            ->method('sendResponse');

        $app->run();
    }

    /**
     * Tests that getting a nonexistent route work as expected;
     */
    public function testRouteNotExists()
    {
        $config = [
            'app_base_path' => __DIR__ . '/test_app',
            'security.config' => [],
        ];

        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $response = new Response('test');

        $app = $this->getMockBuilder(Application::class)
            ->setConstructorArgs([$config])
            ->setMethods(['sessionStart', 'createResponse', 'sendResponse', 'createResponseFromHttpException'])
            ->getMock();
        $app->expects($this->never())
            ->method('createResponse');
        $app->expects($this->once())
            ->method('createResponseFromHttpException')
            ->with($this->isInstanceOf(NotFoundHttpException::class))
            ->willReturn($response);
        $app->expects($this->once())
            ->method('sendResponse')
            ->with($this->identicalTo($response));

        $app->run();
    }

    /**
     * Tests that a route added directly into the 'routing' service gets matched.
     */
    public function testRouteExists()
    {
        $config = [
            'app_base_path' => __DIR__ . '/test_app',
            'security.config' => [],
        ];

        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $route = new Route();
        $route->setPath('/');
        $response = new Response('test');

        $app = $this->getMockBuilder(Application::class)
            ->setConstructorArgs([$config])
            ->setMethods(['sessionStart', 'createResponse', 'sendResponse', 'createResponseFromHttpException'])
            ->getMock();
        $app->expects($this->once())
            ->method('createResponse')
            ->with($this->identicalTo($route))
            ->willReturn($response);
        $app->expects($this->never())
            ->method('createResponseFromHttpException');
        $app->expects($this->once())
            ->method('sendResponse')
            ->with($this->identicalTo($response));

        // Adding a route using the service to be able to match them perfectly.
        $app->get('routing')->addRoute($route);

        $app->run();
    }

    /**
     * Tests that a route added through the helper function gets matched.
     */
    public function testRouteExistsUsingHelper()
    {
        $config = [
            'app_base_path' => __DIR__ . '/test_app',
            'security.config' => [],
        ];

        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $response = new Response('test');

        $app = $this->getMockBuilder(Application::class)
            ->setConstructorArgs([$config])
            ->setMethods(['sessionStart', 'createResponse', 'sendResponse', 'createResponseFromHttpException'])
            ->getMock();
        $app->expects($this->once())
            ->method('createResponse')
            ->willReturn($response);
        $app->expects($this->never())
            ->method('createResponseFromHttpException');
        $app->expects($this->once())
            ->method('sendResponse')
            ->with($this->identicalTo($response));

        // Adding a route using the application helper.
        $app->addRoute('/', '\\stdClass');

        $app->run();
    }
}
