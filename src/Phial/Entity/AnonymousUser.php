<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Entity;

class AnonymousUser extends EntityBase implements UserInterface
{
    public function loggedIn()
    {
        return false;
    }

    public function hasRole($role)
    {
        return false;
    }
}
