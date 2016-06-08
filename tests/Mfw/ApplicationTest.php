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
                ],
                '\\RuntimeException',
                'The "templating" service must implement the TemplatingInterface',
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
                ],
                '\\RuntimeException',
                'Unknown orm type: something-unknown',
            ],
        ];
    }
}
