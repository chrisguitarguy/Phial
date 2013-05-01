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

    public function __construct(array $db_store=array())
    {
        foreach ($db_store as $key => $val) {
            $this[$key] = $val;
        }
    }

    public function loggedIn()
    {
        return true;
    }

    public function hasRole($role)
    {
        return !empty($this['user_role']) && $role === $this['user_role'];
    }

    public function validPassword($pass)
    {
        return $this['user_pass'] && password_verify($pass, $this['user_pass']);
    }

    public function offsetSet($key, $val)
    {
        if ('user_pass' === $key) {
            $val = password_hash($val, PASSWORD_BCRYPT, array('cost' => 12));
        }

        return parent::offsetSet($key, $val);
    }
}
