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
 * For Forgot Password form.
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class ForgotPasswordType extends \Symfony\Component\Form\AbstractType
{
    const NAME = 'forgot_password';

    /**
     * From Symfony\Component\Form\FormTypeInterface
     *
     * {@inheritdoc}
     */
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', 'email', array(
            'label'         => 'Email',
            'constraints'   => array(
                new Assert\NotBlank(),
                new Assert\Email(),
            ),
        ));
    }

    public function getName()
    {
        return static::NAME;
    }
}
