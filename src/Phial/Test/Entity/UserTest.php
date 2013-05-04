<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Test\Entity;

/**
 * Test Phial\Entity\User
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class UserTest extends \PHPUnit_Framework_TestCase
{
    public function testLoggedInReturnsTrue()
    {
        $this->assertTrue($this->getUser()->loggedIn());
    }

    public function testHasRole()
    {
        $user = $this->getUser();

        $this->assertTrue($user->hasRole('loggedin'));

        $this->assertFalse($user->hasRole('admin'));

        $user['user_role'] = 'admin';

        $this->assertTrue($user->hasRole('admin'));

        $this->assertFalse($user->hasRole('editor'));
    }

    public function testPassword()
    {
        $user = $this->getUser();

        $user['user_pass'] = 'password123';

        $this->assertFalse($user->validPassword('password321'));
        $this->assertTrue($user->validPassword('password123'));
    }

    private function getUser(array $vals=array())
    {
        return new \Phial\Entity\User($vals);
    }
}
