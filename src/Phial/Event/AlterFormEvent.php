<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Event;

use Symfony\Component\Form\FormBuilderInterface;

class AlterFormEvent extends \Symfony\Component\EventDispatcher\Event
{
    private $builder;

    private $context;

    public function __construct(FormBuilderInterface $builder, $context)
    {
        $this->builder = $builder;
        $this->context = $context;
    }

    public function setBuilder(FormBuilderInterface $builder)
    {
        $this->builder = $builder;
        return $this;
    }

    public function getBuilder()
    {
        return $this->builder;
    }

    public function getContext()
    {
        return $this->contest;
    }
}
