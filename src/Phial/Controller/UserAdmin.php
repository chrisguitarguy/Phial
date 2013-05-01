<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Controller;

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
        return $this->render('@admin/base.html');
    }
}
