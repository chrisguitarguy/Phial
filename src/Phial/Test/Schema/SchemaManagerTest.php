<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Test\Schema;

use Phial\Schema\SchemaManager;
use Doctrine\DBAL\Schema\Schema as DoctrineSchema;
use Doctrine\DBAL\Platforms;

/**
 * Test Phial\Schema\SchemaManager
 *
 * xxx this actually does some stuff for real with doctrine.
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class SchemaManagerTest extends \PHPUnit_Framework_TestCase
{
    /** Data Providers **********/
    public function platformProvider()
    {
        return array(
            array(new Platforms\MySqlPlatform()),
            array(new Platforms\PostgreSqlPlatform()),
            array(new Platforms\SqlitePlatform()),
        );
    }

    /** Tests **********/

    public function testGetSetDoctrineSchema()
    {
        $sm = new SchemaManager($this->getDoctrineMock());

        $s = $this->getDoctrineMock();

        $this->assertSame($sm, $sm->setDoctrineSchema($s));
        $this->assertSame($s, $sm->getDoctrineSchema());
    }

    public function testAddRemoveSchema()
    {
        $sm = new SchemaManager($this->getDoctrineMock());

        $this->assertFalse($sm->removeSchema('a_schema'));

        $this->assertSame($sm, $sm->addSchema('a_schema', $this->getSchemaMock()));

        $this->assertTrue($sm->removeSchema('a_schema'));
    }

    /**
     * @dataProvider platformProvider
     */
    public function testInstallSql(Platforms\AbstractPlatform $p)
    {
        $schema = new DoctrineSchema();

        $sm = new SchemaManager($schema);

        $sql = $sm->getInstallSql($p);

        $this->assertTrue(is_array($sql));
    }

    /**
     * @dataProvider platformProvider
     */
    public function testMigrateSql(Platforms\AbstractPlatform $p)
    {
        $from = new DoctrineSchema();
        $to = new DoctrineSchema();

        $sm = new SchemaManager($to);

        $sql = $sm->getMigrateSql($from, $p);

        $this->assertTrue(is_array($sql));
    }

    /**
     * @dataProvider platformProvider
     */
    public function testInstallWithActualSchema(Platforms\AbstractPlatform $p)
    {
        $schema = new DoctrineSchema();

        $sm = new SchemaManager($schema);

        foreach ($this->getSchemasToLoad() as $name => $s) {
            $sm->addSchema($name, $s);
        }

        $sql = $sm->getInstallSql($p);

        $this->assertTrue(is_array($sql));
    }

    /**
     * @dataProvider platformProvider
     */
    public function testMigrateWithActualSchema(Platforms\AbstractPlatform $p)
    {
        $from = new DoctrineSchema();
        $to = new DoctrineSchema();

        $sm = new SchemaManager($from);

        foreach ($this->getSchemasToLoad() as $name => $s) {
            $sm->addSchema($name, $s);
        }

        $sql = $sm->getMigrateSql($to, $p);

        $this->assertTrue(is_array($sql));
    }

    private function getSchemaMock()
    {
        return $this->getMock('Phial\\Schema\\SchemaInterface');
    }

    private function getDoctrineMock()
    {
        return $this->getMock('Doctrine\\DBAL\\Schema\\Schema');
    }

    private function getSchemasToLoad()
    {
        return array(
            'users'     => new \Phial\Schema\UserSchema('phial_users'),
        );
    }
}
