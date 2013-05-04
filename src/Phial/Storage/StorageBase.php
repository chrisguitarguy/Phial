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

    protected $logger;

    public function setConnection(\Doctrine\DBAL\Connection $conn)
    {
        $this->conn = $conn;
        return $this;
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function setLogger(\Psr\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
        return $this;
    }

    public function getLogger()
    {
        return $this->logger;
    }

    protected function log($level, $msg, array $ctx=array())
    {
        $logger = $this->getLogger();

        if ($logger) {
            return;
        }

        // so we don't have to do this everywhere, put a special variable
        // in the context for the name of the calling class.
        $ctx['name'] = get_class($this);

        $logger->log($level, $msg, $ctx);
    }
}
