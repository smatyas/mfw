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

namespace Smatyas\Mfw;

use Smatyas\Mfw\Http\Exception\HttpException;
use Smatyas\Mfw\Http\Exception\NotFoundHttpException;
use Smatyas\Mfw\Http\Request;
use Smatyas\Mfw\Router\Router;
use Smatyas\Mfw\Router\RouterInterface;

/**
 * An mfw application.
 */
class Application
{
    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * Runs the application.
     */
    public function run()
    {
        try {
            $request = Request::createFromGlobals();
            $route = $this->getRouter()->match($request);
            if (null === $route) {
                throw new NotFoundHttpException();
            }
            $responseCandidate = $route->handle($request);
        } catch (HttpException $e) {
            var_dump($e);
        }
    }

    /**
     * Gets the router instance.
     *
     * @return RouterInterface
     */
    public function getRouter()
    {
        if (null === $this->router) {
            // Create the default router if nothing is configured yet.
            $router = new Router();
            $this->setRouter($router);
        }

        return $this->router;
    }

    /**
     * Sets the router instance.
     *
     * @param RouterInterface $router
     */
    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;
    }
}
