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
 * Test Phial\Event\AlterFormEvent
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class AlterFormEventTest extends \PHPUnit_Framework_TestCase
{
    public function testGetContext()
    {
        $this->assertEquals('edit', $this->getEvent('edit')->getContext());
    }

    public function testGetSetBuilder()
    {
        $event = $this->getEvent();

        $builder = $this->getBuilderMock();

        $this->assertSame($event, $event->setBuilder($builder));
        $this->assertSame($builder, $event->getBuilder());
    }

    private function getEvent($context='default')
    {
        return new \Phial\Event\AlterFormEvent($this->getBuilderMock(), $context);
    }

    private function getBuilderMock()
    {
        return $this->getMockBuilder('Symfony\Component\Form\FormBuilder')
            ->disableOriginalConstructor()
            ->getMock();
    }
}
