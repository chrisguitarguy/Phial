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

    public function can($cap)
    {
        return 'exist' === $cap ?: false;
    }

    public function addCapability($cap)
    {
        return $this;
    }

    public function removeCapability($cap)
    {
        return false;
    }

    public function setCapabilities(array $caps)
    {
        return $this;
    }

    public function getCapabilities()
    {
        return array();
    }
}
