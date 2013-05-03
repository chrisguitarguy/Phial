<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Provider;

class AdminControllerProvider extends ControllerProviderBase implements \Silex\ControllerProviderInterface
{
    public function connect(\Silex\Application $app)
    {
        $c = $app['controllers_factory'];

        $c->get('/', $this->getController('controller.admin', 'home'))
            ->bind('admin');

        return $c;
    }
}
