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

namespace Smatyas\Mfw\Tests\Http\Exception;

use Smatyas\Mfw\Http\Exception\ForbiddenHttpException;

class ForbiddenHttpExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests that the default constructor works as expected.
     */
    public function testConstructor()
    {
        $exception = new ForbiddenHttpException();
        $this->assertSame('Forbidden', $exception->getMessage());
        $this->assertSame(403, $exception->getResponseCode());
        $this->assertSame(403, $exception->getCode());
    }

    /**
     * Tests that different constructor parameters work as expected.
     *
     * @dataProvider constructorDataProvider
     */
    public function testConstructorWithParameter($message, $expected)
    {
        $exception = new ForbiddenHttpException($message);
        $this->assertSame($expected, $exception->getMessage());
        $this->assertSame(403, $exception->getResponseCode());
        $this->assertSame(403, $exception->getCode());
    }

    public function constructorDataProvider()
    {
        return [
            ['', ''],
            ['Forbidden', 'Forbidden'],
            ['This is not what you are looking for...', 'This is not what you are looking for...'],
        ];
    }
}
