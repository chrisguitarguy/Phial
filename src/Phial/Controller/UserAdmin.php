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
use Symfony\Component\Form\FormInterface;
use Phial\Entity;
use Phial\Form;
use Phial\Event;
use Phial\PhialEvents;

/**
 * Controller for the user admin area.
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class UserAdmin extends Controller
{
    /**
     * @since   0.1
     * @access  protected
     * @var     Phial\Storage\UserStorage
     */
    protected $storage;

    public function __construct(\Phial\Storage\UserStorage $storage)
    {
        $this->storage = $storage;
    }

    public function listAction()
    {
        $users = $this->storage->all();

        foreach ($users as $user) {
            $user['delete'] = $this->getDeleteForm($user)->createView();
        }

        return $this->render('@admin/user_list.html', array(
            'users'     => $users,
        ));
    }

    public function newAction(Request $r)
    {
        $user = new Entity\User();

        $form = $this->getEditForm($user, true);

        if ('POST' === $r->getMethod()) {
            $user_id = $this->saveUser($form, $user, $r, true);

            if ($user_id) {
                return $this->redirectList();
            }
        }

        return $this->render('@admin/user_form.html', array(
            'user'  => $user,
            'form'  => $form->createView(),
        ));
    }

    public function editAction($user_id, Request $r)
    {
        $user = $this->storage->getBy('id', $user_id);

        $form = $this->getEditForm($user, false);

        if ('POST' === $r->getMethod()) {
            $user_id = $this->saveUser($form, $user, $r);

            if ($user_id) {
                return $this->redirectList();
            }
        }

        return $this->render('@admin/user_edit.html', array(
            'user'  => $user,
            'form'  => $form->createView(),
        ));
    }

    public function accountAction(Request $r)
    {
        $user = $this->app['current_user'];

        $form = $this->getEditForm($user, false);

        if ('POST' === $r->getMethod()) {
            $user_id = $this->saveUser($form, $user, $r);

            if ($user_id) {
                return $this->app->redirect($this->url('account.account'));
            }
        }

        return $this->render('@admin/account.html', array(
            'form'  => $form->createView(),
            'user'  => $user,
        ));
    }

    public function deleteAction($user_id, Request $r)
    {
        $user = $this->storage->getBy('id', $user_id);

        if ($user['user_id'] == $this->app['current_user']['user_id']) {
            $this->flash('danger', "You can't delete yourself!");
            return $this->redirectList();
        }

        $form = $this->getDeleteForm($user);

        $form->bind($r);

        if (!$form->isValid()) {
            foreach ($form->getErrors() as $err) {
                $this->flash('danger', $err->getMessage());
            }

            return $this->redirectList();
        }

        $res = false;
        try {
            $this->app['dispatcher']->dispatch(PhialEvents::USERS_DELETE, new Event\AlterUserEvent($user, $r));
            $res = $this->storage->delete($user);
        } catch (\Phial\Exception\PhialException $e) {
            // pass
        }

        if ($res) {
            $this->flash('success', 'User deleted.');
        } else {
            $this->flash('danger', 'Error deleting user.');
        }

        return $this->redirectList();
    }

    private function getEditForm(Entity\UserInterface $user, $new=false)
    {
        $builder = $this->app['form.factory']->createBuilder(new Form\EditUserType($new), $user);

        $event = new Event\AlterFormEvent($builder, $new ? 'new' : 'edit');

        $this->app['dispatcher']->dispatch(PhialEvents::USERS_ALTER_FORM, $event);

        return $event->getBuilder()->getForm();
    }

    private function getDeleteForm(Entity\UserInterface $user)
    {
        $builder = $this->app['form.factory']->createBuilder(new Form\DeleteUserType($new), $user);

        $event = new Event\AlterFormEvent($builder, 'delete');

        $this->app['dispatcher']->dispatch(PhialEvents::USERS_ALTER_FORM, $event);

        return $event->getBuilder()->getForm();
    }

    private function saveUser(FormInterface $form, Entity\UserInterface $user, Request $r, $new=false)
    {
        $form->bind($r);

        if (!$form->isValid()) {
            return false;
        }

        $data = $form->getData();

        if (!empty($data['new_password'])) {
            // they entered one.
            $_password = isset($data['new_password_a']) ? $data['new_password_a'] : false;

            if ($data['new_password'] === $_password) {
                $user['user_pass'] = $data['new_password'];
            } else {
                $this->flash('danger', 'Passwords must match.');
                return false;
            }
        }

        $user_id = false;

        $event = new Event\AlterUserEvent($user, $r);
        $dispatcher = $this->app['dispatcher'];

        try {
            if ($new) {
                $dispatcher->dispatch(PhialEvents::USERS_PRE_CREATE, $event);
                $user_id = $this->storage->create($user);
                $dispatcher->dispatch(PhialEvents::USERS_POST_CREATE, $event);
            } else {
                $dispatcher->dispatch(PhialEvents::USERS_PRE_SAVE, $event);
                $user_id = $this->storage->save($user);
                $dispatcher->dispatch(PhialEvents::USERS_POST_SAVE, $event);
            }
        } catch (\Phial\Exception\EmailExistsException $e) {
            $this->flash('danger', 'That email is already in use.');
        } catch (\Phial\Exception\PhialException $e) {
            $this->flash('danger', 'Something when wrong. Try again?');
        }

        if ($user_id) {
            $this->flash('success', 'User saved.');
        }

        return $user_id;
    }

    private function redirectList()
    {
        return $this->app->redirect(
            $this->url('admin.users.list'),
            303
        );
    }
}
