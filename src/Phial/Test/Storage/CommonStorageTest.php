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
 * Test common operations on storage classes. Like setters/getters from the
 * StorageBase class
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class CommonStorageTest extends StorageTestBase
{
    /** Data Providers **********/

    public function storageInstanceProvider()
    {
        $conn = $this->getConnectionMock();

        // xxx arguments in constructors don't really matter
        // we're not testing any of that stuff here
        return array(
            array(new \Phial\Storage\UserStorage($conn, 'table', 'class')),
        );
    }

    /** Tests **********/

    /**
     * @dataProvider storageInstanceProvider
     */
    public function testGetSetConnection($store)
    {
        $conn = $this->getConnectionMock();

        $this->assertSame($store, $store->setConnection($conn), 'setConnection should return "$this"');
        $this->assertSame($conn, $store->getConnection(), 'getConnection should return the current connection');
    }

    /**
     * @dataProvider storageInstanceProvider
     */
    public function testGetSetLogger($store)
    {
        $log = $this->getLoggerMock();

        $this->assertSame($store, $store->setLogger($log), 'setLogger should return "$this"');
        $this->assertSame($log, $store->getLogger(), 'getLogger should return the current logger');
    }

    /**
     * @dataProvider storageInstanceProvider
     */
    public function testLogWithoutLogger($store)
    {
        $this->assertFalse($store->log('level', 'a message'), "Storage's log method should return false without a logger");
    }

    /**
     * @dataProvider storageInstanceProvider
     * @depends testGetSetLogger
     */
    public function testLogWithLogger($store)
    {
        $log = $this->getLoggerMock();

        $log->expects($this->once())
            ->method('log')
            ->with(
                $this->equalTo('level'),
                $this->equalTo('message'),
                $this->arrayHasKey('name')
            );

        $store->setLogger($log);

        $this->assertTrue($store->log('level', 'message'));
    }
}
