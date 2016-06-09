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
use Smatyas\Mfw\ErrorHandler\EmailErrorHandler;
use Smatyas\Mfw\Http\Exception\HttpException;
use Smatyas\Mfw\Http\Exception\NotFoundHttpException;
use Smatyas\Mfw\Http\Exception\ResponseHttpException;
use Smatyas\Mfw\Http\Request;
use Smatyas\Mfw\Http\Response;
use Smatyas\Mfw\Orm\Manager;
use Smatyas\Mfw\Router\Route;
use Smatyas\Mfw\Router\RouteInterface;
use Smatyas\Mfw\Router\Router;
use Smatyas\Mfw\Router\RouterInterface;
use Smatyas\Mfw\Security\SecurityChecker;
use Smatyas\Mfw\Security\SecurityCheckerInterface;
use Smatyas\Mfw\Templating\Templating;
use Smatyas\Mfw\Templating\TemplatingInterface;

/**
 * An mfw application.
 */
class Application
{
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
        $this->container = new Container();
        $this->getContainer()->add('routing', $this->getConfig()['routing']);
        $this->getContainer()->add('templating', $this->getConfig()['templating']);
        $this->getContainer()->add('security.checker', $this->getConfig()['security.checker']);
        if (isset($this->getConfig()['error_handler'])) {
            $this->getContainer()->add('error_handler', $this->getConfig()['error_handler']);
            $this->get('error_handler')->init();
        }
        if (isset($this->getConfig()['orm.manager'])) {
            $this->getContainer()->add('orm.manager', $this->getConfig()['orm.manager']);
        }
    }

    /**
     * Validates and sets the application configuration.
     *
     * @param $config
     */
    protected function validateAndSetConfig($config)
    {
        foreach (['app_base_path'] as $mandatoryParameter) {
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

        if (isset($config['security.checker']) && !($config['security.checker'] instanceof SecurityCheckerInterface)) {
            throw new \RuntimeException('The "security.checker" service must implement the SecurityCheckerInterface');
        } else {
            if (!isset($config['security.config'])) {
                throw new \RuntimeException('The "security.config" application parameter is missing.');
            }
            $config['security.checker'] = new SecurityChecker($config['security.config']);
        }

        if (isset($config['orm.config'])) {
            if (!isset($config['orm.config']['type'])) {
                throw new \RuntimeException('The "orm.config.type" must be set.');
            }

            switch ($config['orm.config']['type']) {
                case 'mfw':
                    $config['orm.manager'] = new Manager($config['orm.config']);
                    break;

                default:
                    throw new \RuntimeException('Unknown orm type: ' . $config['orm.config']['type']);
                    break;
            }
        }

        if (isset($config['error_handler.config']['type']) && $config['error_handler.config']['type'] === 'email') {
            $config['error_handler'] = new EmailErrorHandler($config['error_handler.config']);
        }

        $this->config = $config;
    }

    /**
     * Runs the application.
     */
    public function run()
    {
        $this->sessionStart();
        try {
            try {
                $request = Request::createFromGlobals();
                $route = $this->getRoute($request);
                $response = $this->createResponse($route, $request);
            } catch (ResponseHttpException $e) {
                if (!($response = $e->getResponse())) {
                    // Throw it further if the exception does not contain a response.
                    throw $e;
                }
            }
        } catch (HttpException $e) {
            $response = $this->createResponseFromHttpException($e);
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
        return $this->getConfig()['app_base_path'];
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
     */
    protected function getRoute(Request $request)
    {
        $route = $this->get('routing')->match($request);
        if (null === $route) {
            throw new NotFoundHttpException();
        }

        $this->get('security.checker')->checkRoute($route);

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
     *
     * @codeCoverageIgnore
     */
    protected function sendResponse(Response $response)
    {
        http_response_code($response->getCode());
        foreach ($response->getHeaders() as $header) {
            header($header);
        }
        print $response->getBody();
    }

    /**
     * Starts the PHP session.
     *
     * @codeCoverageIgnore
     */
    protected function sessionStart()
    {
        session_start();
    }

    /**
     * Creates a Response object from a HttpException.
     *
     * @param HttpException $e
     *
     * @return Response
     */
    protected function createResponseFromHttpException(HttpException $e)
    {
        return new Response($e->getMessage(), $e->getResponseCode());
    }
}
