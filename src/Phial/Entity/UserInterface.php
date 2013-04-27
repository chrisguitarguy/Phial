<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Entity;

interface UserInterface
{
    public function loggedIn();

    public function can($cap);

    public function addCapability($cap);

    public function removeCapability($cap);

    public function setCapabilities(array $caps);

    public function getCapabilities();
}
