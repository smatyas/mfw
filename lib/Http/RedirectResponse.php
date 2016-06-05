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

class RedirectResponse extends Response
{
    /**
     * Creates a new RedirectResponse instance
     */
    public function __construct($location)
    {
        parent::__construct('', 302, ['Location: ' . $location]);
    }
}
