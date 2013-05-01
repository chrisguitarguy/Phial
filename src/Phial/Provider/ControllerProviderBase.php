<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Provider;

abstract class ControllerProviderBase
{
    public function getController($service, $action)
    {
        return sprintf('%s:%sAction', $service, $action);
    }
}
