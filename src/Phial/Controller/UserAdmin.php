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

        return $this->render('@admin/user_list.html', array(
            'users'     => $users,
        ));
    }

    public function newAction(Request $r)
    {
        
    }

    public function editAction($user_id, Request $r)
    {
        
    }

    public function deleteAction($user_id, Request $r)
    {
        
    }
}
