<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Test\Mail;

use Phial\Mail\EmailInterface;

class CommonMailTest extends \PHPUnit_Framework_TestCase
{
    /** Data Providers **********/

    public function instanceProvider()
    {
        $twig = $this->getTwigMock();

        return array(
            array(new \Phial\Mail\ResetPasswordEmail('test@phial.dev', $twig)),
            array(new \Phial\Mail\PasswordNotificationEmail('test@phial.dev', $twig)),
        );
    }

    /** Tests **********/

    /**
     * @dataProvider instanceProvider
     */
    public function testGetSetFrom(EmailInterface $instance)
    {
        $from = 'phail@phial.dev';

        $this->assertSame($instance, $instance->setFrom($from));
        $this->assertEquals($from, $instance->getFrom());
    }

    /**
     * @dataProvider instanceProvider
     */
    public function testGetSetContext(EmailInterface $instance)
    {
        $ctx = array('name' => 'yep');

        $this->assertSame($instance, $instance->setContext($ctx));
        $this->assertEquals($ctx, $instance->getContext());
    }

    /**
     * @dataProvider instanceProvider
     */
    public function testGetSetTwig(EmailInterface $instance)
    {
        $twig = $this->getTwigMock();

        $this->assertSame($instance, $instance->setTwig($twig));
        $this->assertSame($twig, $instance->getTwig());
    }

    /**
     * @dataProvider instanceProvider
     */
    public function testArrayAccess(EmailInterface $instance)
    {
        $this->assertFalse(isset($instance['key']));

        $instance['key'] = 'val';

        $this->assertTrue(isset($instance['key']));

        $this->assertEquals('val', $instance['key']);

        unset($instance['key']);

        // this shouldn't throw a warning...
        unset($instance['no_exists']);
    }

    /**
     * @dataProvider instanceProvider
     */
    public function testBuildEmail(EmailInterface $email)
    {
        $twig = $this->getTwigMock();
        $twig->expects($this->exactly(2))
            ->method('render')
            ->will($this->returnValue('string'));
        
        $msg = $this->getMessageMock();
        $msg->expects($this->once())
            ->method('setFrom')
            ->with($this->isType('string'));

        $msg->expects($this->once())
            ->method('setSubject')
            ->with($this->isType('string'));

        $msg->expects($this->once())
            ->method('setBody')
            ->with($this->isType('string'));

        $msg->expects($this->once())
            ->method('addPart')
            ->with(
                $this->isType('string'),
                $this->equalTo('text/plain')
            );

        $email->setTwig($twig);

        $email->buildEmail($msg);
    }

    /** Helpers **********/

    protected function getMessageMock()
    {
        return $this->getMockBuilder('Swift_Message')
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function getTwigMock()
    {
        return $this->getMockBuilder('Twig_Environment')
            ->disableOriginalConstructor()
            ->getMock();
    }
}
