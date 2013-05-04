<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Schema;

use \Doctrine\DBAL\Schema\Schema;

class UserSchema implements SchemaInterface
{
    protected $user_table;

    public function __construct($user_table)
    {
        $this->user_table = $user_table;
    }

    public function loadTables(Schema $schema)
    {
        $t = $schema->createTable($this->user_table);

        $t->addColumn('user_id', 'bigint', array(
            'unsigned'          => true,
            'autoincrement'     => true,
        ));

        $t->addColumn('user_email', 'string', array(
            'length'            => 128,
        ));

        $t->addColumn('first_name', 'string', array(
            'length'            => 255,
            'notnull'           => false,
            'default'           => null,
        ));

        $t->addColumn('last_name', 'string', array(
            'length'            => 255,
            'notnull'           => false,
            'default'           => null,
        ));


        $t->addColumn('display_name', 'string', array(
            'length'            => 512,
            'notnull'           => false,
            'default'           => null,
        ));

        $t->addColumn('user_pass', 'string', array(
            'length'            => 64,
        ));

        $t->addColumn('user_role', 'string', array(
            'length'            => 64,
        ));

        $t->addColumn('reset_token', 'string', array(
            'length'            => 64,
            'notnull'           => false,
            'default'           => null,
        ));

        $t->setPrimaryKey(array('user_id'));

        $t->addUniqueIndex(array('user_email'), 'users_email_unique');

        $t->addUniqueIndex(array('reset_token'), 'users_reset_unique');
    }
}
