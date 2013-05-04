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
 * Form for deleting users.
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class DeleteUserType extends \Symfony\Component\Form\AbstractType
{
    const NAME = 'user_delete';

    /**
     * From Symfony\Component\Form\FormTypeInterface
     *
     * {@inheritdoc}
     */
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder->add('user_id', 'hidden', array(
            'constraints'   => array(
                new Assert\NotBlank(),
                new Assert\Regex(array(
                    'pattern'   => '/^\d+$/',
                )),
            ),
        ));
    }

    /**
     * From Symfony\Component\Form\FormTypeInterface
     *
     * {@inheritdoc}
     */
    public function getName()
    {
        return static::NAME;
    }
}
