<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Test\Schema;

use Phial\Schema\UserSchema;

/**
 * Test Phial\Schema\UserSchema
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class UserSchemaTest extends \PHPUnit_Framework_TestCase
{
    const TABLE = 'phial_users';

    public function testLoadTables()
    {
        $schema = $this->getMock('Doctrine\\DBAL\\Schema\\Schema');
        $table = $this->getMockBuilder('Doctrine\\DBAL\\Schema\\Table')
            ->disableOriginalConstructor()
            ->getMock();

        $schema->expects($this->once())
            ->method('createTable')
            ->with($this->equalTo(static::TABLE))
            ->will($this->returnValue($table));

        $cols = array(
            'user_id'       => 'bigint',
            'user_email'    => 'string',
            'first_name'    => 'string',
            'last_name'     => 'string',
            'display_name'  => 'string',
            'user_pass'     => 'string',
            'user_role'     => 'string',
        );

        $count = 0;
        foreach ($cols as $col => $type) {
            $table->expects($this->at($count))
                ->method('addColumn')
                ->with(
                    $this->equalTo($col),
                    $this->equalTo($type),
                    $this->isType('array')
                );

            $count++;
        }

        $table->expects($this->once())
            ->method('setPrimaryKey');

        $table->expects($this->once())
            ->method('addUniqueIndex');

        $users = new UserSchema(static::TABLE);

        $users->loadTables($schema);
    }
}
