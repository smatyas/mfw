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

use Smatyas\Mfw\Http\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that the accessor methods work as expected.
     */
    public function testAccessors()
    {
        $request = new Request();
        $this->assertNull($request->getUri());
        $this->assertNull($request->getMethod());
        $this->assertSame([], $request->getGetParameters());
        $this->assertSame([], $request->getPostParameters());

        $uri = 'http://example.com';
        $request->setUri($uri);
        $this->assertSame($uri, $request->getUri());

        $request->setMethod('POST');
        $this->assertSame('POST', $request->getMethod());

        $getParameters = ['test_get_1' => 'value_get_1', 'test_get_2' => 'value_get_2'];
        $request->setGetParameters($getParameters);
        $this->assertSame($getParameters, $request->getGetParameters());

        $postParameters = ['test_post_1' => 'value_post_1', 'test_post_2' => 'value_post_2'];
        $request->setPostParameters($postParameters);
        $this->assertSame($postParameters, $request->getPostParameters());
    }

    /**
     * Tests that the createFromGlobals method works as expected.
     */
    public function testCreateFromGlobals()
    {
        $_SERVER['REQUEST_URI'] = 'http://example.com/request-uri-test';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $getParameters = [
            'test_get_global_1' => 'value_global_get_1',
            'test_get_global_2' => 'value_global_get_2',
        ];
        $_GET = $getParameters;
        $postParameters = [
            'test_post_global_1' => 'value_global_post_1',
            'test_post_global_2' => 'value_global_post_2',
        ];
        $_POST = $postParameters;

        $request = Request::createFromGlobals();
        $this->assertInstanceOf('Smatyas\\Mfw\\Http\\Request', $request);
        $this->assertSame('http://example.com/request-uri-test', $request->getUri());
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame($getParameters, $request->getGetParameters());
        $this->assertSame($postParameters, $request->getPostParameters());
    }

    /**
     * Tests that the getGetParameter method works as expected.
     */
    public function testGetParameter()
    {
        $request = new Request();
        $this->assertNull($request->getGetParameter('test_get_param_1'));
        $this->assertNull($request->getGetParameter('test_get_param_1', null));
        $this->assertSame('default_1', $request->getGetParameter('test_get_param_1', 'default_1'));

        $value = 'param_1_value';
        $request->setGetParameters(['test_get_param_1' => $value]);
        $this->assertSame($value, $request->getGetParameter('test_get_param_1'));
        $this->assertSame($value, $request->getGetParameter('test_get_param_1', null));
        $this->assertSame($value, $request->getGetParameter('test_get_param_1', 'default_1'));
    }

    /**
     * Tests that the getPostParameter method works as expected.
     */
    public function testPostParameter()
    {
        $request = new Request();
        $this->assertNull($request->getPostParameter('test_post_param_1'));
        $this->assertNull($request->getPostParameter('test_post_param_1', null));
        $this->assertSame('default_1', $request->getPostParameter('test_post_param_1', 'default_1'));

        $value = 'param_1_value';
        $request->setPostParameters(['test_post_param_1' => $value]);
        $this->assertSame($value, $request->getPostParameter('test_post_param_1'));
        $this->assertSame($value, $request->getPostParameter('test_post_param_1', null));
        $this->assertSame($value, $request->getPostParameter('test_post_param_1', 'default_1'));
    }
}
