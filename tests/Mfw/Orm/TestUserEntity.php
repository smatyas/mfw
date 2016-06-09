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

namespace Smatyas\Mfw\Tests\Orm;

use Smatyas\Mfw\Security\UserInterface;

class TestUserEntity implements UserInterface
{
    public function getRoles()
    {
        return ['ROLE_1', 'ROLE_2'];
    }

    public function getUsername()
    {
        return 'test_user';
    }
}
