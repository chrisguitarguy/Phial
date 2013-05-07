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
use Phial\Mail;

class Account extends Controller
{
    public function loginAction(Request $r)
    {
        $form = $this->getForm(new Form\LoginType(), 'login');

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
        $form = $this->getForm(new Form\ForgotPasswordType(), 'forgot_password');

        if ('POST' === $r->getMethod()) {
            $form->bind($r);

            if ($form->isValid()) {

                $data = $form->getData();

                if ($url = $this->generateResetKey($data['email'])) {
                    $msg = \Swift_Message::newInstance();

                    $email = $this->app['email.reset_password'];
                    $email['url'] = $url;

                    $email->buildEmail($msg);

                    $msg->setTo($data['email']);

                    $res = false;
                    try {
                        $res = $this->sendEmail($msg, 'forgot_password');
                    } catch (\Exception $e) {
                        // pass
                    }

                    if ($res) {
                        $this->flash('success', 'A password reset email has beent sent.');
                        return $this->app->redirect($this->url('account.login'), 303);
                    }

                    $this->flash('warning', 'An error occured sending the password reset email');
                }
            }
        }

        return $this->render('@admin/forgot_password.html', array(
            'form'  => $form->createView(),
        ));
    }

    public function resetPasswordAction($token, Request $r)
    {
        $user = $this->app['users']->getBy('token', $token);

        $form = $this->getform(new Form\ResetPasswordType(), 'reset_password');

        if ('POST' === $r->getMethod()) {
            $form->bind($r);

            if ($form->isValid()) {
                $data = $form->getData();

                if ($data['new_pass'] === $data['new_pass_again']) {
                    $user['user_pass'] = $data['new_pass'];
                    $user['reset_token'] = null;

                    $res = false;
                    try {
                        $res = $this->app['users']->save($user);
                    } catch (\Exception $e) {
                        // todo logging
                    }

                    if ($res) {
                        $this->flash('success', 'Password reset. Please log in.');

                        $msg = \Swift_Message::newInstance()
                            ->setTo($user['user_email']);

                        $email = $this->app['email.password_notification'];

                        $email->buildEmail($msg);

                        $this->sendEmail($msg, 'password_notification');

                        return $this->app->redirect($this->url('account.login'), 303);
                    }

                    $this->flash('warning', 'Error reseting password.');
                } else {
                    $this->flash('warning', 'Passwords must match.');
                }
            }
        }

        return $this->render('@admin/reset_password.html', array(
            'form'  => $form->createView(),
            'user'  => $user,
            'token' => $token,
        ));
    }

    public function logoutAction(Request $r)
    {
        if ($this->app['session']->invalidate()) {
            $redir = 'account.login';
        } else {
            $this->flash('danger', 'Error logging out. Try again.');
            $redir = 'account.account';
        }

        return $this->app->redirect($this->url($redir));
    }

    public function sendEmail(\Swift_Message $msg, $ctx)
    {
        $event = new Event\AlterEmailEvent($msg, $ctx);

        $this->app['dispatcher']->dispatch(PhialEvents::ACCOUNT_ALTER_EMAIL, $event);

        $msg = $event->getMessage();

        return $this->app['mailer']->send($msg);
    }

    private function getForm(\Symfony\Component\Form\FormTypeInterface $form, $ctx, $object=null)
    {
        $builder = $this->app['form.factory']->createBuilder($form, $object);

        $event = new Event\AlterFormEvent($builder, $ctx);

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

    private function generateResetKey($email)
    {
        $user = $this->getUser($email);

        if (!$user) {
            return false;
        }

        $user['reset_token'] = $this->app['users']->generateResetToken();

        try {
            $this->app['users']->save($user);
        } catch (\Exception $e) {
            $this->flash('danger', 'Something went wrong. Try again?');
            return false;
        }

        return $this->url('account.reset_password', array(
            'token'     => $user['reset_token'],
        ));
    }
}
