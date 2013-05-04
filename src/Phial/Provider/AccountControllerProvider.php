<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Provider;

class AccountControllerProvider extends ControllerProviderBase implements \Silex\ControllerProviderInterface
{
    /**
     * From Silex\ControllerProviderInterface
     * 
     * {@inheritdoc}
     */
    public function connect(\Silex\Application $app)
    {
        $c = $app['controllers_factory'];

        $c->match('/login', $this->getController('controller.account', 'login'))
            ->method('GET|POST')
            ->bind('account.login');

        $c->match('/forgot_password', $this->getController('controller.account', 'forgotPassword'))
            ->method('GET|POST')
            ->bind('account.forgot_password');

        $c->match('/reset_password/{token}', $this->getController('controller.account', 'resetPassword'))
            ->method('GET|POST')
            ->bind('account.reset_password');

        $c->match('/', $this->getController('controller.account', 'account'))
            ->method('GET|POST')
            ->secure('loggedin')
            ->bind('account.account');

        return $c;
    }
}
