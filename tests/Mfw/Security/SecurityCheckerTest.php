<?php
/**
 * This file is part of the mfw package.
 *
 * (c) Mátyás Somfai <somfai.matyas@gmail.com>
 * Created at 2016.06.09.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Smatyas\Mfw\Tests\Security;

use Smatyas\Mfw\Http\Exception\ForbiddenHttpException;
use Smatyas\Mfw\Http\Exception\UnauthorizedHttpException;
use Smatyas\Mfw\Http\RedirectResponse;
use Smatyas\Mfw\Router\Route;
use Smatyas\Mfw\Security\SecurityChecker;
use Smatyas\Mfw\Tests\Orm\TestUserEntity;

class SecurityCheckerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests setting, getting and logging out a user.
     */
    public function testGetSetLogoutUser()
    {
        $user = new TestUserEntity();
        $securityChecker = new SecurityChecker([]);

        $this->assertNull($securityChecker->getUser());

        $securityChecker->setUser($user);
        $this->assertSame($user, $securityChecker->getUser());

        $securityChecker->logoutUser();
        $this->assertNull($securityChecker->getUser());
    }

    /**
     * Tests that the checkRoute method performs as expected.
     *
     * @param $config
     * @param $user
     * @param $expectedException
     * @param $expectedExceptionMessage
     *
     * @throws ForbiddenHttpException
     * @throws UnauthorizedHttpException
     *
     * @dataProvider checkRouteDataProvider
     */
    public function testCheckRoute($config, $user, $expectedException, $expectedExceptionMessage)
    {
        if (null !== $expectedException) {
            $this->expectException($expectedException);
        }
        if (null !== $expectedExceptionMessage) {
            $this->expectExceptionMessage($expectedExceptionMessage);
        }

        $route = new Route();
        $route->setPath('/secured');

        $securityChecker = new SecurityChecker($config);
        if ($user) {
            $securityChecker->setUser($user);
        }

        try {
            $securityChecker->checkRoute($route);
        } catch (UnauthorizedHttpException $e) {
            // Catching UnauthorizedHttpException to check the RedirectResponse set on it.
            if (isset($config['login_path'])) {
                $this->assertInstanceOf(RedirectResponse::class, $e->getResponse());
                $this->assertSame(['Location: ' . $config['login_path']], $e->getResponse()->getHeaders());
            }

            throw $e;
        }
    }

    /**
     * Provides test data for the checkRoute tests.
     */
    public function checkRouteDataProvider()
    {
        $user = new TestUserEntity();
        return [
            [
                [],
                null,
                'expectedException' => null,
                'expectedExceptionMessage' => null,
            ],
            [
                [
                    'paths' => [
                        '/secured' => ['ROLE_1'],
                    ],
                ],
                null,
                'expectedException' => UnauthorizedHttpException::class,
                'expectedExceptionMessage' => 'Unauthorized',
            ],
            [
                [
                    'login_path' => '/login',
                    'paths' => [
                        '/secured' => ['ROLE_1'],
                    ],
                ],
                null,
                'expectedException' => UnauthorizedHttpException::class,
                'expectedExceptionMessage' => 'Unauthorized',
            ],
            [
                [
                    'paths' => [
                        '/secured' => ['ROLE_1'],
                    ],
                ],
                $user,
                'expectedException' => null,
                'expectedExceptionMessage' => null,
            ],
            [
                [
                    'paths' => [
                        '/secured' => ['ROLE_3'],
                    ],
                ],
                $user,
                'expectedException' => ForbiddenHttpException::class,
                'expectedExceptionMessage' => 'Forbidden',
            ],
        ];
    }
}
