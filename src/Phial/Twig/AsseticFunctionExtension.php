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
 * Add a function to twig to render assets from assetic.
 *
 * This is so we don't have to deal with Assetic's twig extension and lading
 * twig templates as resources.
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class AsseticFunctionExtension extends \Twig_Extension
{
    private $am;

    private $escaper;

    public function __construct(\Assetic\AssetManager $am, \Phial\Escaper $esc)
    {
        $this->am = $am;
        $this->escaper = $esc;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'assetic_function';
    }

    public function getFunctions()
    {
        return array(
            'asset'     => new \Twig_Function_Method(
                $this,
                'renderAsset',
                array('is_safe' => array('attribute'))
            ),
        );
    }

    public function renderAsset($name)
    {
        if (!$this->am->has($name)) {
            throw new \InvalidArgumentException(sprintf(
                'Asset manager does not have item "%s"',
                $name
            ));
        }

        $path = $this->am->get($name)->getTargetPath();

        return $this->escaper->leadingslash($path);
    }
}
