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

use Smatyas\Mfw\Container\Container;
use Smatyas\Mfw\Http\Exception\HttpException;
use Smatyas\Mfw\Http\Exception\NotFoundHttpException;
use Smatyas\Mfw\Http\Request;
use Smatyas\Mfw\Http\Response;
use Smatyas\Mfw\Router\Route;
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
     * The application configuration.
     *
     * @var array
     */
    protected $config;

    /**
     * The service container.
     *
     * @var Container
     */
    protected $container;

    /**
     * Creates a new application instance.
     *
     * @param $config
     */
    public function __construct($config)
    {
        $this->validateAndSetConfig($config);
        $this->setAppBasePath($config['app_base_path']);
        $this->container = new Container();
        $this->getContainer()->add('routing', $this->getConfig()['routing']);
        $this->getContainer()->add('templating', $this->getConfig()['templating']);
    }

    /**
     * Validates and sets the application configuration.
     *
     * @param $config
     */
    protected function validateAndSetConfig($config)
    {
        $mandatoryParameters = ['app_base_path'];
        foreach ($mandatoryParameters as $mandatoryParameter) {
            if (!array_key_exists($mandatoryParameter, $config)) {
                throw new \RuntimeException('Mandatory application config parameter missing: ' . $mandatoryParameter);
            }
        }

        if (isset($config['routing']) && !($config['routing'] instanceof RouterInterface)) {
            throw new \RuntimeException('The "routing" service must implement the RouterInterface');
        } else {
            $config['routing'] = new Router();
        }

        if (isset($config['templating']) && !($config['templating'] instanceof TemplatingInterface)) {
            throw new \RuntimeException('The "templating" service must implement the TemplatingInterface');
        } else {
            $config['templating'] = new Templating($config['app_base_path']);
        }

        $this->config = $config;
    }

    /**
     * Runs the application.
     */
    public function run()
    {
        session_start();
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
     * Gets the application configuration.
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Gets the service with the given key.
     *
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->getContainer()->get($key);
    }

    /**
     * Gets the service container.
     *
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Adds a route to the application.
     *
     * @param $path
     * @param $controller
     * @param string $action
     * @param string $method
     */
    public function addRoute($path, $controller, $action = 'index', $method = 'GET')
    {
        $route = new Route();
        $route->setPath($path);
        $route->setController($controller);
        $route->setControllerAction($action);
        $route->setMethod($method);
        $this->get('routing')->addRoute($route);
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
        $route = $this->get('routing')->match($request);
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
        $response = $route->handle($request, $this->getContainer());
        if (!($response instanceof Response)) {
            $rendered = $this->get('templating')->render($route->getTemplatePath(), $response);
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
        foreach ($response->getHeaders() as $header) {
            header($header);
        }
        print $response->getBody();
    }
}
