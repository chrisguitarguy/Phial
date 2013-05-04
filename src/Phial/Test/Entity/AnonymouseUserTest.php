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
 * Test Phial\Entity\AnonymousUser
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class AnonymousUserTest extends \PHPUnit_Framework_TestCase
{
    public function testLoggedInReturnsFalse()
    {
        $this->assertFalse($this->getUser()->loggedIn());
    }

    public function testHasRoleReturnsFalse()
    {
        $roles = array('editor', 'admin');

        $user = $this->getUser();

        foreach ($roles as $role) {
            $this->assertFalse($user->hasRole($role));
        }
    }

    public function testValidPasswordReturnsFalse()
    {
        $this->assertFalse($this->getUser()->validPassword('a password'));
    }

    private function getUser()
    {
        return new \Phial\Entity\AnonymousUser();
    }
}
