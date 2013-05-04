<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Test\Event;

use Phial\Event\GetTemplateEvent;

/**
 * Test Phial\Event\GetTemplateEvent
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class GetTemplateEventTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSetTemplate()
    {
        $event = new GetTemplateEvent('ctx', 'template.html');

        $this->assertEquals('template.html', $event->getTemplate());

        $event->setTemplate('template2.html');

        $this->assertEquals('template2.html', $event->getTemplate());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetTemplateWithBadValue()
    {
        $event = new GetTemplateEvent('ctx', 'template.html');

        $event->setTemplate(123);
    }

    public function testGetContext()
    {
        $context = 'some_route';

        $event = new GetTemplateEvent($context, 'template.html');

        $this->assertEquals($context, $event->getContext());
    }
}
