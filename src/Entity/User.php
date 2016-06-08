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

use Smatyas\Mfw\Security\UserInterface;

class User implements UserInterface
{
    /**
     * The user ID.
     *
     * @var int
     */
    protected $id;
    
    /**
     * The username.
     *
     * @var string
     */
    protected $username;

    /**
     * The password hash.
     *
     * @var string
     */
    protected $password;

    /**
     * The user roles.
     *
     * @var array
     */
    protected $roles;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Calculates the given password's hash.
     *
     * @param $password
     * @param null $salt
     * @return string
     */
    public function hashPassword($password, $salt = null)
    {
        return crypt($password, $salt);
    }

    /**
     * Checks if the given password matches the saved one.
     *
     * @param $password
     * @return bool
     */
    public function matchPassword($password)
    {
        $userString = $this->hashPassword($password, $this->getPassword());
        
        return hash_equals($this->getPassword(), $userString);
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }
}
