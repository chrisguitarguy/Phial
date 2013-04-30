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
class TemplateTagExtension extends \Twig_Extension
{
    private $escaper;

    public function __construct(\Phial\Escaper $escaper)
    {
        $this->escaper = $escaper;
    }

    public function getFunctions()
    {
        return array(
            'language_attributes'   => new \Twig_Function_Method(
                $this,
                'languageAttributes',
                array('is_safe' => 'all')
            ),
            'body_class'            => new \Twig_Function_Method(
                $this,
                'bodyClass',
                array('is_safe' => 'all')
            ),
        );
    }

    public function languageAttributes()
    {
        $atts = array(
            'dir'   => 'ltr',
            'lang'  => 'en-us',
        );

        $out = array();
        foreach ($atts as $key => $val) {
            $out[] = $this->escaper->tag($key) . '="' . $this->escaper->attr($val) . '"';
        }

        return implode(' ', $out);
    }

    public function bodyClass($cls)
    {
        $classes = array(
            'phial',
        );

        $classes[] = $cls;

        $classes = array_map(array($this->escaper, 'attr'), $classes);

        return 'class="' . implode(' ', $classes) . '"';
    }
}
