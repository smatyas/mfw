<?php
/**
 * This file is part of the mfw package.
 *
 * (c) Mátyás Somfai <somfai.matyas@gmail.com>
 * Created at 2016.06.09.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Smatyas\Mfw\Tests\Http\Exception;

use Smatyas\Mfw\Http\Exception\UnauthorizedHttpException;
use Smatyas\Mfw\Http\Response;

class UnauthorizedHttpExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests that the default constructor works as expected.
     */
    public function testConstructor()
    {
        $exception = new UnauthorizedHttpException();
        $this->assertSame('Unauthorized', $exception->getMessage());
        $this->assertSame(401, $exception->getResponseCode());
        $this->assertSame(401, $exception->getCode());
        $this->assertNull($exception->getResponse());
    }

    /**
     * Tests that different constructor parameters work as expected.
     *
     * @dataProvider constructorDataProvider
     */
    public function testConstructorWithParameter($message, $expected, $response)
    {
        $exception = new UnauthorizedHttpException($message, $response);
        $this->assertSame($expected, $exception->getMessage());
        $this->assertSame(401, $exception->getResponseCode());
        $this->assertSame(401, $exception->getCode());
        $this->assertSame($response, $exception->getResponse());
    }

    public function constructorDataProvider()
    {
        return [
            ['', '', null],
            ['Unauthorized', 'Unauthorized', new Response('test1')],
            ['Not today...', 'Not today...', new Response('test2')],
        ];
    }
}
