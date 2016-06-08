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

class ForbiddenHttpException extends HttpException
{
    /**
     * Creates a ForbiddenHttpException instance.
     *
     * @param string $message
     * @param \Exception $previous
     */
    public function __construct($message = 'Forbidden', \Exception $previous = null)
    {
        parent::__construct($message, 403, $previous);
    }
}
