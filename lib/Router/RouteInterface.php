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

namespace Smatyas\Mfw\Router;


use Smatyas\Mfw\Http\Request;

interface RouteInterface
{
    /**
     * Returns if the route matches the given Request.
     *
     * @param Request $request
     * @return bool
     */
    public function matches(Request $request);

    /**
     * Handles the given Request.
     *
     * @param Request $request
     * @return mixed
     */
    public function handle(Request $request);

    /**
     * Returns the template path.
     *
     * @return string
     */
    public function getTemplatePath();
}
