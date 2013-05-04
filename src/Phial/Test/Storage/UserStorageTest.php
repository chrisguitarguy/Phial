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
 * Test Phial\Storage\UserStorage
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class UserStorageTest extends StorageTestBase
{
    const TABLE = 'phial_users';
    const ENTITY = 'Phial\\Entity\\User';

    /** Data Providers **********/

    public function saveCreateProvider()
    {
        return array(
            array('save'),
            array('create'),
        );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSaveThrowsWithoutUserId()
    {
        $store = $this->getStore();

        $store->save($this->getUserMock());
    }

    /**
     * @dataProvider saveCreateProvider
     */
    public function testSaveGoesPerfectly($method)
    {
        $conn = $this->getConnectionMock();
        $conn->expects($this->once())
            ->method('create' === $method ? 'insert' : 'update')
            ->with(
                $this->equalTo(static::TABLE),
                $this->isType('array'),
                $this->isType('array')
            )
            ->will($this->returnValue(1));

        $store = $this->getStore($conn);

        $this->assertEquals(1, call_user_func(array($store, $method), $this->getSavableUser()));
    }

    /**
     * @dataProvider saveCreateProvider
     * @expectedException Phial\Exception\UserSaveException
     */
    public function testSaveThrowsUserSaveException($method)
    {
        $conn = $this->getConnectionMock();
        $conn->expects($this->once())
            ->method('create' === $method ? 'insert' : 'update')
            ->with(
                $this->equalTo(static::TABLE),
                $this->isType('array'),
                $this->isType('array')
            )
            ->will($this->throwException(new \Exception('broken')));

        $store = $this->getStore($conn);

        call_user_func(array($store, $method), $this->getSavableUser());
    }

    /**
     * @dataProvider saveCreateProvider
     * @expectedException Phial\Exception\EmailExistsException
     */
    public function testSaveThrowsEmailExistslException($method)
    {
        $prev = new \Exception('integretiy mock', 23505);
        $conn = $this->getConnectionMock();
        $conn->expects($this->once())
            ->method('create' === $method ? 'insert' : 'update')
            ->with(
                $this->equalTo(static::TABLE),
                $this->isType('array'),
                $this->isType('array')
            )
            ->will($this->throwException(new \Exception('broken', 0, $prev)));

        $store = $this->getStore($conn);

        call_user_func(array($store, $method), $this->getSavableUser());
    }

    private function getStore(\Doctrine\DBAL\Connection $conn=null)
    {
        if (!$conn) {
            $conn = $this->getConnectionMock();
        }

        return new \Phial\Storage\UserStorage(
            $conn,
            static::ENTITY,
            static::TABLE
        );
    }

    private function getUserMock()
    {
        return $this->getMock(static::ENTITY);
    }

    private function getSavableUser()
    {
        $isset_map = array(
            array('user_id', true),
            array('first_name', true),
            array('user_role', true),
        );

        $get_map = array(
            array('user_id', 1),
            array('user_role', 'admin'),
            array('first_name', null),
        );

        $user = $this->getUserMock();
        $user->expects($this->atLeastOnce())
            ->method('offsetExists')
            ->will($this->returnValueMap($isset_map));
        $user->expects($this->atLeastOnce())
            ->method('offsetGet')
            ->will($this->returnValueMap($get_map));

        return $user;
    }
}
