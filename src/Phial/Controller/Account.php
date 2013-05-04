<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Controller;

use Symfony\Component\HttpFoundation\Request;

use Phial\Event;
use Phial\Form;
use Phial\PhialEvents;

class Account extends Controller
{
    public function loginAction(Request $r)
    {
        $form = $this->getLoginForm();

        if ('POST' === $r->getMethod()) {
            $form->bind($r);

            if ($form->isValid()) {
                $data = $form->getData();

                $user = $this->getUser($data['email']);

                if ($user && $user->validPassword($data['pass'])) {
                    $this->app['session']->set('user_id', $user['user_id']);

                    $this->app['session']->migrate();

                    return $this->app->redirect($this->url('admin'), 303);
                } else {
                    $this->flash('danger', 'Invalid password.');
                }
            }
        }

        return $this->render('@admin/login.html', array(
            'form'  => $form->createView(),
        ));
    }

    public function forgotPasswordAction(Request $r)
    {
        
    }

    public function resetPasswordAction($token, Request $r)
    {
        
    }

    public function accountAction(Request $r)
    {
        
    }

    private function getLoginForm()
    {
        $builder = $this->app['form.factory']->createBuilder(new Form\LoginType());

        $event = new Event\AlterFormEvent($builder, 'login');

        $this->app['dispatcher']->dispatch(PhialEvents::ACCOUNT_ALTER_FORM, $event);

        return $event->getBuilder()->getForm();
    }

    private function getUser($email)
    {
        $user = false;

        try {
            $user = $this->app['users']->getBy('email', $email);
        } catch (\Phial\Exception\UserNotFoundException $e) {
            $this->flash('danger', 'Invalid email.');
        } catch (\Exception $e) {
            $this->flash('warning', 'Something when wrong.');
        }

        return $user;
    }
}
