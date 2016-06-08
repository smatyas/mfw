<?php
/**
 * This file is part of the mfw package.
 *
 * (c) Mátyás Somfai <somfai.matyas@gmail.com>
 * Created at 2016.06.07.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Smatyas\Mfw\Tests\Templating;

use Smatyas\Mfw\Templating\Templating;

class TemplatingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the default values and that the accessor methods work as expected.
     */
    public function testDefaultsAndAccessors()
    {
        $templating = new Templating('test');
        $this->assertSame('test', $templating->getAppBasePath());

        $templating->setAppBasePath('test2');
        $this->assertSame('test2', $templating->getAppBasePath());
    }

    /**
     * Tests that the the correct exception is thrown when the template file is unreadable.
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessageRegExp /^Cannot read template file: .*unreadable\.html\.tpl$/
     */
    public function testUnreadableTemplate()
    {
        $templating = new Templating(__DIR__ . '/..');
        $templating->render('unreadable.html.tpl');
    }

    /**
     * Tests that the render method performs as expected.
     *
     * @param $template
     * @param $parameters
     * @param $expectedOutput
     *
     * @dataProvider renderDataProvider
     */
    public function testRender($template, $parameters, $expectedOutput)
    {
        $templating = new Templating(__DIR__ . '/..');

        $this->assertSame($expectedOutput, $templating->render($template, $parameters));
    }

    /**
     * Returns test data for the testRender tests.
     */
    public function renderDataProvider()
    {
        return [
            [
                'test1.html.tpl',
                [],
                'content
',
            ],
            [
                'test1.html.tpl',
                ['var1' => 'value1'],
                'content
',
            ],
            [
                'test2.html.tpl',
                ['var1' => 'value1'],
                'content1
value1
content2
{{$var2}}
',
            ],
            [
                'test2.html.tpl',
                ['var2' => 'value2'],
                'content1
{{$var1}}
content2
value2
',
            ],
            [
                'test2.html.tpl',
                ['var1' => 'value1', 'var2' => 'value2'],
                'content1
value1
content2
value2
',
            ],
        ];
    }
}
