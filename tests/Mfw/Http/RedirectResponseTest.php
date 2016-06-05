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

use Smatyas\Mfw\Http\RedirectResponse;

class RedirectResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests that the constructor works as expected.
     *
     * @param $location
     *
     * @dataProvider constructorDataProvider
     */
    public function testConstructor($location)
    {
        $response = new RedirectResponse($location);
        $this->assertSame('', $response->getBody());
        $this->assertSame(302, $response->getCode());
        $this->assertSame(['Location: ' . $location], $response->getHeaders());
    }

    /**
     * Provides test data for the constructor tests.
     */
    public function constructorDataProvider()
    {
        return [
            ['http://example.com'],
            ['/test'],
        ];
    }
}
