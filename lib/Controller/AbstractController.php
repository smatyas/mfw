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

use Smatyas\Mfw\Router\TemplatingInterface;

abstract class AbstractController implements ControllerInterface
{
    /**
     * @var TemplatingInterface
     */
    protected $templating;

    /**
     * @return TemplatingInterface
     */
    public function getTemplating()
    {
        return $this->templating;
    }

    /**
     * @param TemplatingInterface $templating
     */
    public function setTemplating($templating)
    {
        $this->templating = $templating;
    }
}
