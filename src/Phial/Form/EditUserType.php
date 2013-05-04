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
 * Form type for editing users.
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class EditUserType extends \Symfony\Component\Form\AbstractType
{
    /**
     * Is this for a new user?
     *
     * @since   0.1
     * @access  private
     * @var     boolean
     */
    private $new_user;

    public function __construct($new=false)
    {
        $this->new_user = $new;
    }

    /**
     * From Symfony\Component\Form\FormTypeInterface
     *
     * {@inheritdoc}
     */
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder->add('user_email', 'email', array(
            'label'         => 'Email',
            'constraints'   => array(
                new Assert\NotBlank(),
                new Assert\Email(),
            ),
        ));

        $builder->add('user_role', 'choice', array(
            'label'         => 'Role',
            'choices'       => array(
                'admin'  => 'Admin',
                'editor' => 'Editor',
            ),
            'constraints'   => array(
                new Assert\NotBlank(),
            ),
        ));

        $builder->add('first_name', 'text', array(
            'label'     => 'First Name',
            'required'  => false,
        ));

        $builder->add('last_name', 'text', array(
            'label'     => 'Last Name',
            'required'  => false,
        ));

        $builder->add('display_name', 'text', array(
            'label'     => 'Display Name',
            'required'  => false,
        ));

        $builder->add('new_password', 'password', array(
            'label'         => 'Password',
            'constraints'   => $this->new_user ? array(new Assert\NotBlank()) : array(),
            'required'      => $this->new_user,
        ));

        $builder->add('new_password_a', 'password', array(
            'label'         => 'Password Again',
            'constraints'   => $this->new_user ? array(new Assert\NotBlank()) : array(),
            'required'      => $this->new_user,
        ));
    }

    /**
     * From Symfony\Component\Form\FormTypeInterface
     *
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'user_edit';
    }
}
