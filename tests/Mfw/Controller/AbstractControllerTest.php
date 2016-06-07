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

namespace Smatyas\Mfw\Tests\Controller;

use Smatyas\Mfw\Container\Container;
use Smatyas\Mfw\Controller\ControllerInterface;

class AbstractControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testContainer()
    {
        $dateTime = new \DateTime();
        $container = new Container();
        $container->add('date_time', $dateTime);

        /** @var ControllerInterface $controller */
        $controller = $this->getMockForAbstractClass('Smatyas\Mfw\Controller\AbstractController');
        $controller->setContainer($container);
        $this->assertSame($dateTime, $controller->get('date_time'));
    }
}
