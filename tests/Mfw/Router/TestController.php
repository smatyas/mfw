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

namespace Smatyas\Mfw\Tests\Router;

use Smatyas\Mfw\Container\Container;
use Smatyas\Mfw\Controller\ControllerInterface;

class TestController implements ControllerInterface
{
    /**
     * {@inheritdoc}
     */
    public function setContainer(Container $container)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function get($service)
    {
        return null;
    }

    public function indexAction()
    {
        return ['method' => __METHOD__];
    }
}
