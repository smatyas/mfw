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

interface RouterInterface
{
    /**
     * Adds a router to the router.
     *
     * @param Route $route
     */
    public function addRoute(Route $route);

    /**
     * Finds the first route that matches the request.
     *
     * @param Request $request
     * @return RouteInterface
     */
    public function match(Request $request);
}
