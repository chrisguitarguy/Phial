<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Test\Event;

use Phial\Event\AlterUserEvent;

/**
 * Test Phial\Event\AlterUserEvent
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class AlterUserEventTest extends \PHPUnit_Framework_TestCase
{
    public function testGetUser()
    {
        $user = $this->getUserMock();
        $r = $this->getRequestMock();

        $event = new AlterUserEvent($user, $r);

        $this->assertSame($user, $event->getUser());
    }

    public function testGetRequest()
    {
        $user = $this->getUserMock();
        $r = $this->getRequestMock();

        $event = new AlterUserEvent($user, $r);

        $this->assertSame($r, $event->getRequest());
    }

    private function getUserMock()
    {
        return $this->getMock('Phial\\Entity\\User');
    }

    private function getRequestMock()
    {
        return $this->getMockBuilder('Symfony\\Component\\HttpFoundation\\Request')
            ->disableOriginalConstructor()
            ->getMock();
    }
}
