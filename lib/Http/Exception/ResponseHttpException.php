<?php
/**
 * This file is part of the mfw package.
 *
 * (c) Mátyás Somfai <somfai.matyas@gmail.com>
 * Created at 2016.06.08.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Smatyas\Mfw\Http\Exception;

use Smatyas\Mfw\Http\Response;

abstract class ResponseHttpException extends HttpException
{
    /**
     * @var Response
     */
    protected $response;

    /**
     * Gets the response.
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Sets the response.
     *
     * @param Response $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }
}
