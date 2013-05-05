<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Test\Event;

/**
 * Test Phial\Event\AlterEmailEvent
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class AlterEmailEventTest extends \PHPUnit_Framework_TestCase
{
    public function testGetContext()
    {
        $this->assertEquals('forgot', $this->getEvent('forgot')->getContext());
    }

    public function testGetSetMessage()
    {
        $msg = $this->getMessageMock();

        $event = $this->getEvent();

        $this->assertSame($event, $event->setMessage($msg));
        $this->assertSame($msg, $event->getMessage());
    }

    private function getEvent($context='default')
    {
        return new \Phial\Event\AlterEmailEvent($this->getMessageMock(), $context);
    }

    public function getMessageMock()
    {
        return $this->getMockBuilder('Swift_Message')
            ->disableOriginalConstructor()
            ->getMOck();
    }
}
