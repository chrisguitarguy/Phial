<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Test\Form;

/**
 * Some utilities for testing form types.
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
abstract class FormTestBase extends \PHPUnit_Framework_TestCase
{
    protected function getBuilderMock()
    {
        return $this->getMockBuilder('Symfony\\Component\\Form\\FormBuilder')
            ->disableOriginalConstructor()
            ->getMock();
    }
}
