<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Event;

/**
 * Fired when a user is "altered": created, saved, deleted, etc.
 *
 * Deliberately doesn't have a setter for the user.
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class AlterUserEvent extends \Symfony\Component\EventDispatcher\Event
{
    private $user;
    private $request;

    public function __construct(\Phial\Entity\UserInterface $user,
        \Symfony\Component\HttpFoundation\Request $r)
    {
        $this->user = $user;
        $this->request = $r;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getRequest()
    {
        return $this->request;
    }
}
