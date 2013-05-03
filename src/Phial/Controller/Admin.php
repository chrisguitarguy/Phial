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
 * Constroller for everything that doesn't fit into the UserAdmin or ContentAdmin
 * controllers.
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class Admin extends Controller
{
    public function homeAction()
    {
        return 'hello, for now';
    }
}
