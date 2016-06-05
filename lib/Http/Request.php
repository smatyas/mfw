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
     * Creates a Request instance from the server globals.
     *
     * @return Request
     */
    public static function createFromGlobals()
    {
        $request = new self();
        $request->setUri($_SERVER['REQUEST_URI']);
        $request->setMethod($_SERVER['REQUEST_METHOD']);

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
}
