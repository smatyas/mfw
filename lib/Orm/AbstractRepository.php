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

abstract class AbstractRepository
{
    /**
     * THe database connection.
     *
     * @var \PDO
     */
    protected $db;

    /**
     * Creates a new repository instance.
     *
     * @param \PDO $db
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Returns the database connection instance.
     *
     * @return \PDO
     */
    public function getDb()
    {
        return $this->db;
    }
}
