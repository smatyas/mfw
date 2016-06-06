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
     * Returns the routes.
     *
     * @return mixed
     */
    public function getRoutes();
    
    /**
     * Adds a route to the router.
     *
     * @param RouteInterface $route
     */
    public function addRoute(RouteInterface $route);

    /**
     * Finds the first route that matches the given Request.
     *
     * @param Request $request
     * @return RouteInterface
     */
    public function match(Request $request);
}
