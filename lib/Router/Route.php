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

use Smatyas\Mfw\Container\Container;
use Smatyas\Mfw\Controller\ControllerInterface;
use Smatyas\Mfw\Http\Request;

class Route implements RouteInterface
{
    /**
     * The route path.
     *
     * @var string
     */
    protected $path;

    /**
     * The accepted HTTP method.
     *
     * @var string
     */
    protected $method = 'GET';

    /**
     * The controller to call.
     *
     * @var string
     */
    protected $controller;

    /**
     * The controller action to call.
     *
     * @var string
     */
    protected $controllerAction = 'index';

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param string $controller
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    }

    /**
     * Returns the controller action method to call.
     *
     * @return string
     */
    public function getControllerAction()
    {
        return $this->controllerAction . 'Action';
    }

    /**
     * Sets the controller action method.
     *
     * @param string $controllerAction
     */
    public function setControllerAction($controllerAction)
    {
        $this->controllerAction = $controllerAction;
    }

    /**
     * Returns the controller instance to use.
     *
     * @return ControllerInterface
     */
    protected function getControllerInstance()
    {
        $controller = $this->getController();
        if (!class_exists($controller)) {
            throw new \RuntimeException('Missing controller class: ' . $controller);
        }
        $controllerInstance = new $controller();
        if (!($controllerInstance instanceof ControllerInterface)) {
            throw new \RuntimeException(sprintf(
                'The "%s" controller must implement the ControllerInterface interface.',
                $controller
            ));
        }

        return $controllerInstance;
    }

    /**
     * {@inheritdoc}
     */
    public function matches(Request $request)
    {
        return ($this->getMethod() === $request->getMethod() && $this->getPath() === $request->getUri());
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Request $request, Container $container)
    {
        $controller = $this->getControllerInstance();
        $controller->setContainer($container);
        $action = $this->getControllerAction();

        return $controller->$action($request);
    }

    /**
     * Returns the template file name.
     *
     * @return string
     */
    protected function getTemplateFileName()
    {
        return $this->controllerAction . '.html.tpl';
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplatePath()
    {
        $controllerNamespaceParts = explode('\\', $this->getController());
        return sprintf(
            '%s/%s',
            end($controllerNamespaceParts),
            $this->getTemplateFileName()
        );
    }
}
