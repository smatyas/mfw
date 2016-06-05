<?php
/**
 * This file is part of the mfw package.
 *
 * (c) Mátyás Somfai <somfai.matyas@gmail.com>
 * Created at 2016.06.05.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Smatyas\Mfw\Tests\Container;

use Smatyas\Mfw\Container\Container;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Container
     */
    private $container;

    protected function setUp()
    {
        $this->container = new Container();
    }

    protected function tearDown()
    {
        unset($this->container);
    }

    /**
     * Provides service names for the tests.
     */
    public function serviceNameDataProvider()
    {
        return [
            ['routing'],
            ['templating'],
            ['entity_manager'],
            ['anything'],
        ];
    }

    /**
     * Tests that a new container does not contain services.
     *
     * @param $key
     *
     * @dataProvider serviceNameDataProvider
     */
    public function testMethods($key)
    {
        $this->assertFalse($this->container->has($key));
    }

    /**
     * Tests that trying to get an unset service results in the correct exception.
     *
     * @param $key
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessageRegExp /^No such service:/
     * @dataProvider serviceNameDataProvider
     */
    public function testGetException($key)
    {
        $this->container->get($key);
    }

    /**
     * Tests that trying to remove an unset service results in the correct exception.
     *
     * @param $key
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessageRegExp /^No such service:/
     * @dataProvider serviceNameDataProvider
     */
    public function testRemoveException($key)
    {
        $this->container->remove($key);
    }

    /**
     * Tests that adding and removing services works as expected.
     */
    public function testAddAndRemove()
    {
        // Make sure they are not added yet.
        $this->assertFalse($this->container->has('test1'));
        $this->assertFalse($this->container->has('test2'));

        $testService1 = new \stdClass();
        $testService2 = new \stdClass();
        $this->container->add('test1', $testService1);
        $this->container->add('test2', $testService2);

        // Test that they are present in the container.
        $this->assertTrue($this->container->has('test1'));
        $this->assertTrue($this->container->has('test2'));

        // Test that the getter returns the correct instances.
        $this->assertSame($testService1, $this->container->get('test1'));
        $this->assertSame($testService2, $this->container->get('test2'));

        // Test removing the services.
        $this->container->remove('test1');
        $this->assertFalse($this->container->has('test1'));
        $this->assertTrue($this->container->has('test2'));
        $this->container->remove('test2');
        $this->assertFalse($this->container->has('test1'));
        $this->assertFalse($this->container->has('test2'));
    }
}
