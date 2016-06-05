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

class Router implements RouterInterface
{
    /**
     * The routes.
     *
     * @var array
     */
    protected $routes;

    /**
     * Creates a Router instance.
     */
    public function __construct()
    {
        $this->routes = [];
    }

    /**
     * {@inheritdoc}
     */
    public function addRoute(Route $route)
    {
        $this->routes[] = $route;
    }

    /**
     * {@inheritdoc}
     */
    public function match(Request $request)
    {
        foreach ($this->routes as $route) {
            /** @var $route RouteInterface */
            if ($route->matches($request)) {
                return $route;
            }
        }

        return null;
    }
}
