<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Schema;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\Schema;

class SchemaManager
{
    const VER = 1;

    protected $schemas;

    public function addSchema($name, SchemaInterface $schema)
    {
        $this->schemas[$name] = $schema;
        return $this;
    }

    public function removeSchema($name)
    {
        if (isset($this->schemas[$name])) {
            unset($this->schemas[$name]);
            return true;
        }

        return false;
    }

    public function getInstallSql(AbstractPlatform $platform)
    {
        $schema = $this->getSchemaObject();

        return $schema->toSql($platform);
    }

    public function getMigrateSql(Schema $from, AbstractPlatform $platform)
    {
        $to = $this->getSchemaObject();

        return $from->getMigrateToSql($to, $platform);
    }

    protected function getSchemaObject()
    {
        $schema = new Schema();

        $this->loadAllTables($schema);

        return $schema;
    }

    protected function loadAllTables(\Doctrine\DBAL\Schema\Schema $schema)
    {
        foreach ($this->schemas as $name => $obj) {
            $obj->loadTables($schema);
        }
    }
}
