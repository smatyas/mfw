<?php
/**
 * This file is part of the mfw package.
 *
 * (c) Mátyás Somfai <somfai.matyas@gmail.com>
 * Created at 2016.06.07.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Smatyas\MfwApp\Entity;

use Smatyas\Mfw\Orm\AbstractRepository;

class UserRepository extends AbstractRepository
{
    /**
     * Finds the user by the given username.
     *
     * @param $username
     * @return null|User
     */
    public function findByUsername($username)
    {
        $sql = 'select * from user where username = :username';
        $stmt = $this->getDb()->prepare($sql);
        if (!$stmt->execute(['username' => $username])) {
            throw new \RuntimeException(json_encode($stmt->errorInfo()));
        }
        $userData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        switch (true) {
            case (count($userData) > 1):
                throw new \RuntimeException('Unexpected query result size: ' . count($userData));
                break;

            case (count($userData) === 1):
                $user = new User();
                $user->setId($userData[0]['id']);
                $user->setUsername($userData[0]['username']);
                $user->setPassword($userData[0]['password']);
                $user->setRoles(json_decode($userData[0]['roles']));
                return $user;

            default:
                return null;
        }
    }
}
