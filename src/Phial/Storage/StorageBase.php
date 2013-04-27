<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Storage;

abstract class StorageBase
{
    protected $conn;

    public function setConnection(\Doctrine\DBAL\Connection $conn)
    {
        $this->conn = $conn;
        return $this;
    }

    public function getConnection()
    {
        return $this->conn;
    }
}
