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


class HttpException extends \Exception
{
    /**
     * Creates a HttpException instance.
     *
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($message, $code, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Returns the HTTP response code.
     *
     * @return int
     */
    public function getResponseCode()
    {
        return $this->getCode();
    }
}
