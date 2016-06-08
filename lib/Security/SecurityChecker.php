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
use Smatyas\Mfw\Http\Exception\UnauthorizedHttpException;
use Smatyas\Mfw\Http\RedirectResponse;
use Smatyas\Mfw\Router\RouteInterface;

class SecurityChecker implements SecurityCheckerInterface
{
    /**
     * The configuration.
     *
     * @var array
     */
    protected $config;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function checkRoute(RouteInterface $route)
    {
        if (isset($this->config['paths']) && is_array($this->config['paths'])) {
            foreach ($this->config['paths'] as $path => $roles) {
                if ($path === $route->getPath()) {
                    // This route is secured, need to authorize the user.
                    if (!$this->getUser()) {
                        // There is no logged in user, need to authenticate first.
                        $unauthorizedHttpException = new UnauthorizedHttpException();
                        if (isset($this->config['login_path'])) {
                            $unauthorizedHttpException->setResponse(
                                new RedirectResponse($this->config['login_path'])
                            );
                        }
                        throw $unauthorizedHttpException;
                    }

                    // There is a logged in user who needs to be authorized.
                    $authorized = array_intersect($roles, $this->getUser()->getRoles());
                    if (!$authorized) {
                        throw new ForbiddenHttpException();
                    }
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getUser()
    {
        return isset($_SESSION['user']) ? $_SESSION['user'] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function setUser(UserInterface $user)
    {
        $_SESSION['user'] = $user;
    }

    /**
     * {@inheritdoc}
     */
    public function logoutUser()
    {
        unset($_SESSION['user']);
    }
}
