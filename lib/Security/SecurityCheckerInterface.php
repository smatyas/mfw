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

use Smatyas\Mfw\Http\Exception\ForbiddenHttpException;
use Smatyas\Mfw\Http\Exception\RedirectException;
use Smatyas\Mfw\Http\Exception\UnauthorizedHttpException;
use Smatyas\Mfw\Router\RouteInterface;

interface SecurityCheckerInterface
{
    /**
     * Creates a new instance of the security checker.
     *
     * @param array $config
     */
    public function __construct(array $config);

    /**
     * Checks if the given route is accessible by the current user.
     *
     * @param RouteInterface $route
     *
     * @throws UnauthorizedHttpException
     *   When there is no logged in user.
     *
     * @throws ForbiddenHttpException
     *   When the current user has insufficient roles.
     */
    public function checkRoute(RouteInterface $route);

    /**
     * Gets the current user.
     *
     * @return UserInterface
     */
    public function getUser();

    /**
     * Sets the current user.
     */
    public function setUser(UserInterface $user);

    /**
     * Logs out the current user.
     */
    public function logoutUser();
}
