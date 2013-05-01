<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Entity;

abstract class EntityBase implements \ArrayAccess
{
    protected $storage = array();

    public function setStorage(array $store)
    {
        $this->storage = $store;
        return $this;
    }

    public function getStorage()
    {
        return $this->storage;
    }

    /** ArrayAccess **********/

    public function offsetExists($key)
    {
        return array_key_exists($key, $this->storage);
    }

    public function offsetGet($key)
    {
        if ($this->offsetExists($key)) {
            return $this->storage[$key];
        }

        $this->sendWarning($key);
    }

    public function offsetUnset($key)
    {
        if ($this->offsetExists($key)) {
            unset($this->storage[$key]);
        } else {
            $this->sendWarning($key);
        }
    }

    public function offsetSet($key, $val)
    {
        $this->storage[$key] = $val;
    }

    /** Property Setters/Getters **********/

    public function __isset($key)
    {
        return $this->offsetExists($key);
    }

    public function __get($key)
    {
        return $this->offsetGet($key);
    }

    public function __unset($key)
    {
        return $this->offsetUnset($key);
    }

    public function __set($key, $val)
    {
        return $this->offsetSet($key);
    }

    private function sendWarning($key)
    {
        trigger_error(
            sprintf('"%s" does not not have key "%s"', get_class($this), $key),
            E_USER_NOTICE
        );
    }
}
