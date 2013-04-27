<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Schema;

interface SchemaInterface
{
    public function loadTables(\Doctrine\DBAL\Schema\Schema $schema);
}
