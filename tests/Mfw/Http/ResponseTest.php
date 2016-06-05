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

namespace Smatyas\Mfw\Tests\Http;

use Smatyas\Mfw\Http\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests that the default constructor works as expected.
     */
    public function testDefaultConstructor()
    {
        $response = new Response('');
        $this->assertSame('', $response->getBody());
        $this->assertSame(200, $response->getCode());
        $this->assertSame([], $response->getHeaders());
    }

    /**
     * Tests that different constructor parameters work as expected.
     *
     * @param $body
     * @param $code
     * @param $headers
     *
     * @dataProvider constructorParametersDataProvider
     */
    public function testConstructorParameters($body, $code, $headers)
    {
        $response = new Response($body, $code, $headers);
        $this->assertSame($body, $response->getBody());
        $this->assertSame($code, $response->getCode());
        $this->assertSame($headers, $response->getHeaders());
    }

    /**
     * Provides data for the testConstructorParameters tests.
     */
    public function constructorParametersDataProvider()
    {
        return [
            ['test1', 100, []],
            ['test2', 200, ['X-Test' => 'x-test-1']],
            ['test3', 404, ['X-Test' => 'x-test-2']],
        ];
    }

    /**
     * Test that the accessor methods work as expected.
     */
    public function testAccessors()
    {
        $response = new Response('accessors-test');

        $this->assertSame('accessors-test', $response->getBody());
        $response->setBody('body-mod');
        $this->assertSame('body-mod', $response->getBody());

        $this->assertSame(200, $response->getCode());
        $response->setCode(500);
        $this->assertSame(500, $response->getCode());

        $this->assertSame([], $response->getHeaders());
        $response->setHeaders(['X-Custom-1' => 'test']);
        $this->assertSame(['X-Custom-1' => 'test'], $response->getHeaders());
    }
}
