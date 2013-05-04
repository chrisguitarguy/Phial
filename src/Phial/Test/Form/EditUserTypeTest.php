<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Test\Form;

use Phial\Form\EditUserType;

/**
 * Test Phial\Form\EditUserType
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class EditUserTypeTest extends FormTestBase
{
    public function testGetName()
    {
        $type = new EditUserType();

        $this->assertEquals(EditUserType::NAME, $type->getName());
    }

    public function testBuildForm()
    {
        $type = new EditUserType();

        $fields = array(
            'user_email',
            'user_role',
            'first_name',
            'last_name',
            'display_name',
            'new_password',
            'new_password_a',
        );

        $builder = $this->getBuilderMock();

        foreach ($fields as $idx => $f) {
            $builder->expects($this->at($idx))
                ->method('add')
                ->with(
                    $this->equalTo($f),
                    $this->isType('string'),
                    $this->isType('array')
                );
        }

        $type->buildForm($builder, array());
    }
}
