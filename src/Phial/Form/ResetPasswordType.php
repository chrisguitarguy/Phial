<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Form;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * For the reset password form
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class ResetPasswordType extends \Symfony\Component\Form\AbstractType
{
    const NAME = 'reset_password';

    /**
     * From Symfony\Component\Form\FormTypeInterface
     *
     * {@inheritdoc}
     */
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder->add('new_pass', 'password', array(
            'label'         => 'New Password',
            'constraints'   => array(
                new Assert\NotBlank(),
            ),
        ));

        $builder->add('new_pass_again', 'password', array(
            'label'         => 'New Password Again',
            'constraints'   => array(
                new Assert\NotBlank(),
            ),
        ));
    }

    public function getName()
    {
        return static::NAME;
    }
}
