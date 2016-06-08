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

namespace Smatyas\Mfw\Tests\Orm;

use Smatyas\Mfw\Orm\AbstractRepository;

class AbstractRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests that the constructors and the getDb methods work as expected.
     */
    public function testDb()
    {
        $db = $this->createMock('\PDO');

        /** @var AbstractRepository $repo */
        $repo = $this->getMockForAbstractClass('Smatyas\Mfw\Orm\AbstractRepository', [$db]);
        $this->assertSame($db, $repo->getDb());
    }
}
