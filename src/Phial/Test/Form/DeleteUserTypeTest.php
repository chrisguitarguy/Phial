<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Test\Form;

use Phial\Form\DeleteUserType;

/**
 * Test Phial\Form\DeleteUserType
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class DeleteUserTypeTest extends FormTestBase
{
    public function testGetName()
    {
        $type = new DeleteUserType();

        $this->assertEquals(DeleteUserType::NAME, $type->getName());
    }

    public function testBuildForm()
    {
        $type = new DeleteUserType();

        $builder = $this->getBuilderMock();

        $builder->expects($this->once())
            ->method('add')
            ->with(
                $this->equalTo('user_id'),
                $this->isType('string'),
                $this->isType('array')
            );

        $type->buildForm($builder, array());
    }
}
