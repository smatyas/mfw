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

namespace Smatyas\Mfw\Router;


interface TemplatingInterface
{
    /**
     * Returns the rendered template.
     *
     * @param $template
     * @param $parameters
     * @return string
     */
    public function render($template, $parameters = []);
}
