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

class UnauthorizedHttpException extends ResponseHttpException
{
    /**
     * Creates a UnauthorizedHttpException instance.
     *
     * @param string $message
     * @param Response $response
     * @param \Exception $previous
     */
    public function __construct(
        $message = 'Unauthorized',
        Response $response = null,
        \Exception $previous = null
    ) {
        parent::__construct($message, 401, $previous);
        $this->setResponse($response);
    }
}
