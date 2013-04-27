<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Entity;

class User extends EntityBase implements UserInterface
{
    private $caps = array();

    public function __construct(array $db_store=array(), array $caps=array())
    {
        $this->setStorage($db_store);
        $this->setCapabilities($caps);
    }

    public function loggedIn()
    {
        return true;
    }

    public function hasRole($role)
    {
        return !empty($this['user_role']) && $role === $this['user_role'];
    }
}
