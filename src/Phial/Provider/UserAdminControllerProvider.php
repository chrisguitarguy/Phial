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

        $c->match('/users/new', $this->getController('controller.user_admin', 'new'))
            ->method('GET|POST')
            ->bind('admin.users.new');

        $c->match('/users/edit/{user_id}', $this->getController('user.user_admin', 'edit'))
            ->method('GET|POST')
            ->assert('user_id', '\d+')
            ->bind('admin.users.edit');

        $c->post('/users/delete/{user_id}', $this->getController('controller.user_admin', 'delete'))
            ->assert('user_id', '\d+')
            ->bind('admin.users.delete');

        return $c;
    }
}
