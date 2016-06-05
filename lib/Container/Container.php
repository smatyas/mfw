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

namespace Smatyas\Mfw\Container;


class Container
{
    /**
     * The container.
     *
     * @var array
     */
    protected $container = [];

    /**
     * Adds a service to the container.
     *
     * @param $key
     * @param $service
     */
    public function add($key, $service)
    {
        if ($this->has($key)) {
            throw new \RuntimeException('Service key already exists: ' . $key);
        }

        $this->container[$key] = $service;
    }

    /**
     * Removes a service from the container.
     *
     * @param $key
     */
    public function remove($key)
    {
        if (!$this->has($key)) {
            throw new \RuntimeException('No such service: ' .  $key);
        }

        unset($this->container[$key]);
    }

    /**
     * Gets the service with the given key.
     *
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        if (!$this->has($key)) {
            throw new \RuntimeException('No such service: ' .  $key);
        }

        return $this->container[$key];
    }

    /**
     * Returns if the service with the given key exists.
     *
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->container);
    }
}
