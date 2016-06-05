<?php
/**
 * This file is part of the mfw package.
 *
 * (c) Mátyás Somfai <somfai.matyas@gmail.com>
 * Created at 2016.06.05.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Smatyas\Mfw\Http;


class Response
{
    /**
     * The HTTP response code.
     *
     * @var int
     */
    protected $code;

    /**
     * The HTTP response body.
     *
     * @var string
     */
    protected $body;

    /**
     * Creates a Response instance.
     *
     * @param $body
     * @param int $code
     */
    public function __construct($body, $code = 200)
    {
        $this->setBody($body);
        $this->setCode($code);
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }
}
