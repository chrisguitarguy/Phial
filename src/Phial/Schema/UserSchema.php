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

    protected $caps_table;

    protected $caps_user_table;

    public function __construct($user_table, $caps_table, $caps_user_table)
    {
        $this->user_table = $user_table;
        $this->caps_table = $caps_table;
        $this->caps_user_table = $caps_user_table;
    }

    public function loadTables(Schema $schema)
    {
        $this->createUserTable($schema);
        $this->createCapabilityTable($schema);
        $this->createJoinTable($schema);
    }

    protected function createUserTable(Schema $schema)
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

        $t->setPrimaryKey(array('user_id'));

        $t->addUniqueIndex(array('user_email'), 'users_email_unique');
    }

    protected function createCapabilityTable(Schema $schema)
    {
        $t = $schema->createTable($this->caps_table);

        $t->addColumn('cap_id', 'bigint', array(
            'unsigned'          => true,
            'autoincrement'     => true,
        ));

        $t->addColumn('cap_name', 'string', array(
            'length'            => 64,
        ));

        $t->addColumn('cap_desc', 'text', array(
            'notnull'           => false,
            'default'           => null,
        ));

        $t->setPrimaryKey(array('cap_id'));

        $t->addUniqueIndex(array('cap_name'), 'cap_name_unique');
    }

    protected function createJoinTable(Schema $schema)
    {
        $t = $schema->createTable($this->caps_user_table);

        $t->addColumn('user_id', 'bigint', array(
            'unsigned'      => true,
        ));

        $t->addColumn('cap_id', 'bigint', array(
            'unsigned'      => true,
        ));

        $t->addUniqueIndex(array('user_id', 'cap_id'), 'user_cap_unique');

        $t->addForeignKeyConstraint(
            $this->user_table,
            array('user_id'),
            array('user_id'),
            array('onDelete' => 'CASCADE'),
            'user_caps_user_ref'
        );

        $t->addForeignKeyConstraint(
            $this->caps_table,
            array('cap_id'),
            array('cap_id'),
            array('onDelete' => 'CASCADE'),
            'user_caps_cap_ref'
        );
    }
}
