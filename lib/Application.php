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
use Smatyas\Mfw\Http\Response;
use Smatyas\Mfw\Router\RouteInterface;
use Smatyas\Mfw\Router\Router;
use Smatyas\Mfw\Router\RouterInterface;
use Smatyas\Mfw\Router\Templating;
use Smatyas\Mfw\Router\TemplatingInterface;

/**
 * An mfw application.
 */
class Application
{
    /**
     * The base path of the application.
     *
     * @var string
     */
    protected $appBasePath;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var TemplatingInterface
     */
    protected $templating;

    /**
     * Runs the application.
     */
    public function run()
    {
        try {
            $request = Request::createFromGlobals();
            $route = $this->getRoute($request);
            $response = $this->createResponse($route, $request);
        } catch (HttpException $e) {
            $response = new Response($e->getMessage(), $e->getResponseCode());
        }
        $this->sendResponse($response);
    }

    /**
     * Gets the application base path.
     *
     * @return string
     */
    public function getAppBasePath()
    {
        if (null === $this->appBasePath) {
            $this->setAppBasePath(__DIR__);
        }

        return $this->appBasePath;
    }

    /**
     * Sets the application base path.
     *
     * @param string $appBasePath
     */
    public function setAppBasePath($appBasePath)
    {
        $this->appBasePath = $appBasePath;
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

    /**
     * Gets the templateing instance.
     *
     * @return TemplatingInterface
     */
    public function getTemplating()
    {
        if (null === $this->templating) {
            // Create the default templating if nothing is configured yet.
            $templating = new Templating($this->getAppBasePath());
            $this->setTemplating($templating);
        }

        return $this->templating;
    }

    /**
     * Sets the templating instance.
     *
     * @param TemplatingInterface $templating
     */
    public function setTemplating($templating)
    {
        $this->templating = $templating;
    }

    /**
     * Gets the Route that matches the request.
     *
     * @param Request $request
     * @return RouteInterface
     * @throws NotFoundHttpException
     *   When there were no matched route.
     */
    protected function getRoute(Request $request)
    {
        $route = $this->getRouter()->match($request);
        if (null === $route) {
            throw new NotFoundHttpException();
        }
        return $route;
    }

    /**
     * Creates the Response object using the matched route.
     *
     * @param RouteInterface $route
     * @param Request $request
     * @return Response
     */
    protected function createResponse(RouteInterface $route, Request $request)
    {
        $response = $route->handle($request);
        if (!($response instanceof Response)) {
            $rendered = $this->getTemplating()->render($route->getTemplatePath(), $response);
            $response = new Response($rendered);
            return $response;
        }
        return $response;
    }

    /**
     * Sends the response to the client.
     *
     * @param Response $response
     */
    protected function sendResponse(Response $response)
    {
        http_response_code($response->getCode());
        print $response->getBody();
    }
}
