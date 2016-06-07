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

namespace Smatyas\Mfw\Orm;

class Manager
{
    /**
     * The ORM configuration.
     *
     * @var array
     */
    protected $config;

    /**
     * The database connection.
     *
     * @var \PDO
     */
    protected $db;

    /**
     * Creates a new Manager instance.
     *
     * @param $config
     */
    public function __construct($config)
    {
        $this->setConfig($config);
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setConfig($config)
    {
        $this->validateConfig($config);
        $this->config = $config;
    }

    /**
     * Validates the given configuration array.
     *
     * @param $config
     */
    protected function validateConfig($config)
    {
        foreach (['host', 'database', 'username', 'password', 'mapping'] as $parameter) {
            if (!isset($config[$parameter])) {
                throw new \RuntimeException(sprintf('The "%s" parameter must be configured.', $parameter));
            }
        }
    }

    /**
     * Returns the database connection instance.
     *
     * @return \PDO
     */
    public function getDb()
    {
        if (null === $this->db) {
            $this->db = new \PDO(
                sprintf('mysql:dbname=%s;host=%s', $this->config['database'], $this->config['host']),
                $this->config['username'],
                $this->config['password']
            );
        }

        return $this->db;
    }

    /**
     * Returns the repository object for the given class.
     *
     * @param $class
     * @return mixed
     */
    public function getRepository($class)
    {
        if (!in_array($class, $this->config['mapping'])) {
            throw new \RuntimeException('There is no mapping info for the class: ' . $class);
        }

        $repositoryClass = $class . 'Repository';
        if (!class_exists($repositoryClass)) {
            throw new \RuntimeException('Missing repository class: ' . $repositoryClass);
        }

        $repository = new $repositoryClass($this->getDb());
        if (!($repository instanceof AbstractRepository)) {
            throw new \RuntimeException(
                sprintf('The "%s" repository class must extend AbstractRepository.', $repositoryClass)
            );
        }

        return $repository;
    }
}
