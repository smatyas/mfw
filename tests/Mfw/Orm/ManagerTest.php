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

namespace Smatyas\Mfw\Tests\Orm;

use Smatyas\Mfw\Orm\AbstractRepository;
use Smatyas\Mfw\Orm\Manager;

class ManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the constructor and config validation.
     *
     * @param $config
     * @param $expectedException
     * @param $expectedExceptionMessage
     *
     * @dataProvider configDataProvider
     */
    public function testConstructor($config, $expectedException, $expectedExceptionMessage)
    {
        if (null !== $expectedException) {
            $this->expectException($expectedException);
        }
        if (null !== $expectedExceptionMessage) {
            $this->expectExceptionMessage($expectedExceptionMessage);
        }

        $manager = new Manager($config);

        if (null === $expectedException) {
            $this->assertSame($config, $manager->getConfig());
        }
    }

    /**
     * Provides test configurations for the testConstructor tests.
     */
    public function configDataProvider()
    {
        return [
            [
                'config' => [
                    'database' => 'mfw',
                    'username' => 'mfw',
                    'password' => 'mfw',
                    'mapping' => [
                        'user' => 'Smatyas\\Mfw\\Tests\\Orm\\TestEntity',
                    ],
                ],
                'expectedException' => '\\RuntimeException',
                'expectedExceptionMessage' => 'The "host" parameter must be configured.',
            ],
            [
                'config' => [
                    'host' => 'db',
                    'username' => 'mfw',
                    'password' => 'mfw',
                    'mapping' => [
                        'user' => 'Smatyas\\Mfw\\Tests\\Orm\\TestEntity',
                    ],
                ],
                'expectedException' => '\\RuntimeException',
                'expectedExceptionMessage' => 'The "database" parameter must be configured.',
            ],
            [
                'config' => [
                    'host' => 'db',
                    'database' => 'mfw',
                    'password' => 'mfw',
                    'mapping' => [
                        'user' => 'Smatyas\\Mfw\\Tests\\Orm\\TestEntity',
                    ],
                ],
                'expectedException' => '\\RuntimeException',
                'expectedExceptionMessage' => 'The "username" parameter must be configured.',
            ],
            [
                'config' => [
                    'host' => 'db',
                    'database' => 'mfw',
                    'username' => 'mfw',
                    'mapping' => [
                        'user' => 'Smatyas\\Mfw\\Tests\\Orm\\TestEntity',
                    ],
                ],
                'expectedException' => '\\RuntimeException',
                'expectedExceptionMessage' => 'The "password" parameter must be configured.',
            ],
            [
                'config' => [
                    'host' => 'db',
                    'database' => 'mfw',
                    'username' => 'mfw',
                    'password' => 'mfw',
                ],
                'expectedException' => '\\RuntimeException',
                'expectedExceptionMessage' => 'The "mapping" parameter must be configured.',
            ],
            [
                'config' => [
                    'host' => 'db',
                    'database' => 'mfw',
                    'username' => 'mfw',
                    'password' => 'mfw',
                    'mapping' => [
                        'user' => 'Smatyas\\Mfw\\Tests\\Orm\\TestEntity',
                    ],
                ],
                'expectedException' => null,
                'expectedExceptionMessage' => null,
            ],
        ];
    }

    /**
     * Tests that the getDb method creates the PDO object once.
     */
    public function testGetDbCreatesPdo()
    {
        $config = [
            'host' => 'db',
            'database' => 'mfw-db',
            'username' => 'mfw-user',
            'password' => 'mfw-pass',
            'mapping' => [
                'user' => 'Smatyas\\Mfw\\Tests\\Orm\\TestEntity',
            ],
        ];

        $db = $this->createMock('\PDO');

        /** @var Manager $mockedManager */
        $mockedManager = $this->getMockBuilder(Manager::class)
            ->setConstructorArgs([$config])
            ->setMethods(['createPdo'])
            ->getMock();
        $mockedManager->expects($this->once())
            ->method('createPdo')
            ->with(
                $this->identicalTo('mysql:dbname=mfw-db;host=db'),
                $this->identicalTo('mfw-user'),
                $this->identicalTo('mfw-pass')
            )
            ->willReturn($db);

        $this->assertSame($db, $mockedManager->getDb());
        $this->assertSame($db, $mockedManager->getDb()); // second call to test that createPdo is called only once
    }

    /**
     * Tests that the getRepository method performs as expected.
     *
     * @param $class
     * @param $mapping
     * @param $expectedException
     * @param $expectedExceptionMessage
     *
     * @dataProvider getRepositoryDataProvider
     */
    public function testGetRepository($class, $mapping, $expectedException, $expectedExceptionMessage)
    {
        $config = [
            'host' => 'db',
            'database' => 'mfw-db',
            'username' => 'mfw-user',
            'password' => 'mfw-pass',
            'mapping' => $mapping,
        ];
        $db = $this->createMock('\PDO');

        /** @var Manager $mockedManager */
        $mockedManager = $this->getMockBuilder(Manager::class)
            ->setConstructorArgs([$config])
            ->setMethods(['createPdo'])
            ->getMock();
        $mockedManager->expects($this->any())
            ->method('createPdo')
            ->willReturn($db);

        if (null !== $expectedException) {
            $this->expectException($expectedException);
        }
        if (null !== $expectedExceptionMessage) {
            $this->expectExceptionMessage($expectedExceptionMessage);
        }

        $repo = $mockedManager->getRepository($class);

        if (null === $expectedException) {
            $this->assertInstanceOf($class . 'Repository', $repo);
            $this->assertInstanceOf(AbstractRepository::class, $repo);
        }
    }

    /**
     * Provides test data for the testGetRepository tests.
     */
    public function getRepositoryDataProvider()
    {
        return [
            [
                TestEntity::class,
                [],
                '\\RuntimeException',
                'There is no mapping info for the class: ' . TestEntity::class,
            ],
            [
                TestEntity::class,
                ['another_mapped_class' => TestEntity2::class],
                '\\RuntimeException',
                'There is no mapping info for the class: ' . TestEntity::class,
            ],
            [
                TestEntity::class,
                ['test' => TestEntity::class],
                null,
                null,
            ],
            [
                TestEntity2::class,
                ['test' => TestEntity2::class],
                '\\RuntimeException',
                'Missing repository class: ' . TestEntity2::class . 'Repository',
            ],
            [
                TestEntity3::class,
                ['test' => TestEntity3::class],
                '\\RuntimeException',
                'The "' . TestEntity3::class . 'Repository" repository class must extend AbstractRepository.',
            ],
        ];
    }
}
