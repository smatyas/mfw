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

namespace Smatyas\Mfw\Security;

interface UserInterface
{
    /**
     * Gets the granted roles.
     *
     * @var array
     */
    public function getRoles();

    /**
     * Gets the username.
     *
     * @return string
     */
    public function getUsername();
}
