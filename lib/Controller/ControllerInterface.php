<?php
/**
 * This file is part of the mfw package.
 *
 * (c) Mátyás Somfai <somfai.matyas@gmail.com>
 * Created at 2016.06.03.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Smatyas\Mfw\Controller;

use Smatyas\Mfw\Container\Container;

interface ControllerInterface
{
    /**
     * Sets the service container.
     *
     * @param Container $container
     */
    public function setContainer(Container $container);

    /**
     * Gets the named service.
     *
     * @param $service
     * @return mixed
     */
    public function get($service);
}
