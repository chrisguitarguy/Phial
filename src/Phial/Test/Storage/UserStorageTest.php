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

    public function getByColumnProvider()
    {
        return array(
            array('ID', 'user_id'),
            array('id', 'user_id'),
            array('user_id', 'user_id'),
            array('email', 'user_email'),
            array('user_email', 'user_email'),
            array('token', 'reset_token'),
            array('reset_token', 'reset_token'),
            array('reset', 'reset_token'),
        );
    }

    /** Tests **********/

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

    /**
     * @expectedException InvalidArgumentException
     */
    public function testDeleteWithoutId()
    {
        $store = $this->getStore();

        $store->delete($this->getUserMock());
    }

    public function testDeleteGoesWell()
    {
        $conn = $this->getConnectionMock();
        $conn->expects($this->once())
            ->method('delete')
            ->with(
                $this->equalTo(static::TABLE),
                $this->arrayHasKey('user_id'),
                $this->arrayHasKey('user_id')
            )
            ->will($this->returnValue(1));

        $store = $this->getStore($conn);

        $this->assertEquals(1, $store->delete($this->getSavableUser()));
    }

    /**
     * @expectedException Phial\Exception\UserDeleteException
     */
    public function testDeleteThrows()
    {
        $conn = $this->getConnectionMock();
        $conn->expects($this->once())
            ->method('delete')
            ->with(
                $this->equalTo(static::TABLE),
                $this->arrayHasKey('user_id'),
                $this->arrayHasKey('user_id')
            )
            ->will($this->throwException(new \Exception('delete fail')));

        $store = $this->getStore($conn);

        $store->delete($this->getSavableUser());
    }

    /**
     * @dataProvider getByColumnProvider
     */
    public function testGetBy($column, $contains)
    {
        $conn = $this->getConnectionMock();
        $conn->expects($this->once())
            ->method('fetchAssoc')
            ->with(
                $this->stringContains($contains),
                $this->arrayHasKey('value'),
                $this->arrayHasKey('value')
            )
            ->will($this->returnValue(array('user_id' => 1)));

        $store = $this->getStore($conn);

        $res = $store->getBy($column, 1); // xxx second argument doesn't matter here

        $this->assertInstanceOf(static::ENTITY, $res);
    }

    /**
     * @dataProvider getByColumnProvider
     */
    public function testGetByWithRaw($column, $contains)
    {
        $conn = $this->getConnectionMock();
        $conn->expects($this->once())
            ->method('fetchAssoc')
            ->with(
                $this->stringContains($contains),
                $this->arrayHasKey('value'),
                $this->arrayHasKey('value')
            )
            ->will($this->returnValue(array('user_id' => 1)));

        $store = $this->getStore($conn);

        $res = $store->getBy($column, 1, true); // xxx second argument doesn't matter here

        $this->assertTrue(is_array($res));
        $this->assertArrayHasKey('user_id', $res);
    }

    /**
     * @dataProvider getByColumnProvider
     * @expectedException Phial\Exception\UserNotFoundException
     */
    public function testGetWithoutResult($column, $contains)
    {
        $conn = $this->getConnectionMock();
        $conn->expects($this->once())
            ->method('fetchAssoc')
            ->with(
                $this->stringContains($contains),
                $this->arrayHasKey('value'),
                $this->arrayHasKey('value')
            )
            ->will($this->returnValue(false));

        $store = $this->getStore($conn);

        $res = $store->getBy($column, 1); // xxx second argument doesn't matter here
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetByWithBadColumn()
    {
        $store = $this->getStore();

        $store->getBy('bad_column', 'nope');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAllWithBadPage()
    {
        $store = $this->getStore();

        $store->all(false, 0);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAllWithBadLimit()
    {
        $store = $this->getStore();

        $store->all(false, 1, 'asdf');
    }

    /**
     * @expectedException Phial\Exception\NoUsersFoundException
     */
    public function testAllNoUsersFound()
    {
        $conn = $this->getConnectionMock();
        $conn->expects($this->once())
            ->method('fetchAll')
            ->will($this->returnValue(false));

        $store = $this->getStore($conn);

        $store->all();
    }

    public function testAllReturnsArray()
    {
        $conn = $this->getConnectionMock();
        $conn->expects($this->once())
            ->method('fetchAll')
            ->will($this->returnValue(array(
                array('user_id' => 1),
                array('user_id' => 2),
            )));

        $store = $this->getStore($conn);

        $res = $store->all();

        $this->assertTrue(is_array($res));
        $this->assertCount(2, $res);
        $this->assertInstanceOf(static::ENTITY, $res[0]);
    }

    public function testAllReturnsArrayWithRaw()
    {
        $conn = $this->getConnectionMock();
        $conn->expects($this->once())
            ->method('fetchAll')
            ->will($this->returnValue(array(
                array('user_id' => 1),
                array('user_id' => 2),
            )));

        $store = $this->getStore($conn);

        $res = $store->all(true);

        $this->assertTrue(is_array($res));
        $this->assertCount(2, $res);
        $this->assertTrue(is_array($res[0]));
    }

    public function testResetToken()
    {
        $conn = $this->getConnectionMock();
        $conn->expects($this->at(0))
            ->method('fetchColumn')
            ->will($this->returnValue(1));
        $conn->expects($this->at(1))
            ->method('fetchColumn')
            ->will($this->returnValue(null));

        $store = $this->getStore($conn);

        $this->assertTrue(is_string($store->generateResetToken()));
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
