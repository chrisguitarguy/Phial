<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Test\Storage;

/**
 * Base class for storage tests. Provides a few utilities for getting mocks
 * that we need and such.
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
abstract class StorageTestBase extends \PHPUnit_Framework_TestCase
{
    protected function getConnectionMock()
    {
        return $this->getMockBuilder('Doctrine\\DBAL\\Connection')
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function getLoggerMock()
    {
        return $this->getMock('Psr\\Log\\LoggerInterface');
    }
}
