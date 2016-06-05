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

namespace Smatyas\Mfw\Http\Exception;


class NotFoundHttpException extends HttpException
{
    /**
     * Creates a NotFoundHttpException instance.
     */
    public function __construct($message = 'Not found.', \Exception $previous = null)
    {
        parent::__construct($message, 404, $previous);
    }
}
