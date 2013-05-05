<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Event;

class AlterEmailEvent extends \Symfony\Component\EventDispatcher\Event
{
    private $msg;

    private $context;

    public function __construct(\Swift_Message $msg, $context)
    {
        $this->msg = $msg;
        $this->context = $context;
    }

    public function setMessage(\Swift_Message $msg)
    {
        $this->msg = $msg;
        return $this;
    }

    public function getMessage()
    {
        return $this->msg;
    }

    public function getContext()
    {
        return $this->context;
    }
}
