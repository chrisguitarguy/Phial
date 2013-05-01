<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Provider;

class UserAdminControllerProvider extends ControllerProviderBase implements \Silex\ControllerProviderInterface
{
    public function connect(\Silex\Application $app)
    {
        $c = $app['controllers_factory'];

        $c->get('/users', $this->getController('controller.user_admin', 'list'))
            ->bind('admin.users.list');

        return $c;
    }
}
