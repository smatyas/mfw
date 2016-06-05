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

namespace Smatyas\Mfw\Tests\Http\Exception;

use Smatyas\Mfw\Http\Exception\HttpException;

class HttpExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests that the methods work as expected.
     *
     * @param $message
     * @param $code
     *
     * @dataProvider getExceptionDataProvider
     */
    public function testAccessors($message, $code)
    {
        $exception = new HttpException($message, $code);
        $this->assertSame($message, $exception->getMessage());
        $this->assertSame($code, $exception->getResponseCode());
        $this->assertSame($code, $exception->getCode());
    }

    /**
     * Provides test data for the testAccessors test.
     */
    public function getExceptionDataProvider()
    {
        $data = [];

        for ($i = 0; $i < 1000; $i += 100) {
            $data[] = [
                'message-' . $i,
                $i,
            ];
        }

        return $data;
    }
}
