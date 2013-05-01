<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Event;

/**
 * Used by controllers to fetch a template.
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class GetTemplateEvent extends \Symfony\Component\EventDispatcher\Event
{
    /**
     * Our template identifier.
     *
     * @since   0.1
     * @access  private
     * @var     string
     */
    private $template;

    /**
     * The context in which the template is being render. Eg. a route name.
     *
     * @since   0.1
     * @access  private
     * @var     string
     */
    private $context;

    /**
     * Constructor. Set the default template.
     *
     * @since   0.1
     * @access  public
     * @param   string $template
     * @return  void
     */
    public function __construct($context, $template)
    {
        $this->context = $context;
        $this->setTemplate($template);
    }

    /**
     * Get the template.
     *
     * @since   0.1
     * @access  public
     * @param   string $template
     * @return  Phial\Event\GetTemplateEvent
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Get the context.
     *
     * @since   0.1
     * @access  public
     * @return  string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set the template.
     *
     * @since   0.1
     * @access  public
     * @param   string $template
     * @throws  InvalidArgumentException if template is not a string
     * @return  Phial\Event\GetTemplateEvent
     */
    public function setTemplate($template)
    {
        if (!is_string($template)) {
            throw new \InvalidArgumentException(sprintf(
                "Template must be a string, %s given",
                gettype($template)
            ));
        }

        $this->template = $template;

        return $this;
    }
}
