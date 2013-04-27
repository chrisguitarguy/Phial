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

    }

    public function loggedIn()
    {
        return true;
    }

    public function can($cap)
    {
        return 'exist' === $cap ?: !empty($this->caps[$cap]);
    }

    public function addCapability($cap)
    {
        $this->caps[$cap] = true;
        return $this;
    }

    public function removeCapability($cap)
    {
        if (isset($this->caps[$cap])) {
            unset($this->caps[$cap]);
            return true;
        }

        return false;
    }

    public function setCapabilities(array $caps)
    {
        $this->caps = array_combine($caps, array_fill(0, count($caps), true));
        return $this;
    }

    public function getCapabilities()
    {
        return $this->caps;
    }
}
