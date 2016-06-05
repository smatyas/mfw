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

namespace Smatyas\Mfw\Http;

class Request
{
    /**
     * The request URI uri.
     *
     * @var string
     */
    protected $uri;

    /**
     * The HTTP method.
     *
     * @var string
     */
    protected $method;

    /**
     * The GET parameters.
     *
     * @var array
     */
    protected $getParameters = [];

    /**
     * The POST parameters.
     *
     * @var array
     */
    protected $postParameters = [];

    /**
     * Creates a Request instance from the server globals.
     *
     * @return Request
     */
    public static function createFromGlobals()
    {
        $request = new self();
        $request->setUri($_SERVER['REQUEST_URI']);
        $request->setMethod($_SERVER['REQUEST_METHOD']);
        $request->setGetParameters($_GET);
        $request->setPostParameters($_POST);

        return $request;
    }

    /**
     * Returns the request URI uri.
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Sets the request URI uri.
     *
     * @param string $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
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
     * @return array
     */
    public function getGetParameters()
    {
        return $this->getParameters;
    }

    /**
     * @param array $getParameters
     */
    public function setGetParameters($getParameters)
    {
        $this->getParameters = $getParameters;
    }

    public function getGetParameter($parameter, $default = null)
    {
        $parameters = $this->getGetParameters();

        return isset($parameters[$parameter]) ? $parameters[$parameter] : $default;
    }

    /**
     * @return array
     */
    public function getPostParameters()
    {
        return $this->postParameters;
    }

    /**
     * @param array $postParameters
     */
    public function setPostParameters($postParameters)
    {
        $this->postParameters = $postParameters;
    }

    public function getPostParameter($parameter, $default = null)
    {
        $parameters = $this->getPostParameters();
        
        return isset($parameters[$parameter]) ? $parameters[$parameter] : $default;
    }
}
