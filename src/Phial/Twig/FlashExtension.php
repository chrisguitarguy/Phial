<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Twig;

/**
 * Add some "template tags" to twig.
 *
 * @since    0.1
 */
class FlashExtension extends \Twig_Extension
{
    private $env;

    private $template;

    public function __construct($tmp)
    {
        $this->template = $tmp;
    }

    public function initRuntime(\Twig_Environment $env)
    {
        $this->env = $env;
    }

    public function setTemplate($t)
    {
        $this->template = $t;
        return $this;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function getFunctions()
    {
        return array(
            'flash_messages' => new \Twig_Function_Method(
                $this,
                'flashMessage',
                array('is_safe' => array('all'))
            ),
        );
    }

    public function getName()
    {
        return 'flash_msg';
    }

    public function flashMessage(\Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface $bag)
    {
        return $this->env->render($this->getTemplate(), array(
            'all_messages'     => $bag->all(),
        ));
    }
}
