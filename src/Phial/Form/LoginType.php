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
 * The login form.
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class LoginType extends \Symfony\Component\Form\AbstractType
{
    const NAME = 'login';

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

        $builder->add('pass', 'password', array(
            'label'         => 'Password',
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
