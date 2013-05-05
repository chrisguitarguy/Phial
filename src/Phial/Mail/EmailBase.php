<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Mail;

/**
 * Base class for emails.
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
abstract class EmailBase implements EmailInterface, \ArrayAccess
{
    /**
     * @since   0.1
     * @access  protected
     * @var     string
     */
    protected $from_email;

    /**
     * Variable to pass into the twig context.
     *
     * @since   0.1
     * @access  protected
     * @var     array
     */
    protected $context;

    /**
     * @since   0.1
     * @access  protected
     * @var     Twig_Environment
     */
    protected $twig;

    /**
     * Constructor. Set the template and from email.
     *
     * @since   0.1
     * @access  public
     * @param   string $template
     * @param   string $from_email
     * @return  void
     */
    public function __construct($from_email, \Twig_Environment $twig, array $context=array())
    {
        $this->setFrom($from_email);
        $this->setTwig($twig);
        $this->setContext($context);
    }

    /** EmailInterface **********/

    /**
     * {@inheritdoc}
     */
    public function buildEmail(\Swift_Message $msg)
    {
        $msg->setFrom($this->getFrom());
        $msg->setSubject($this->getSubject());
        $msg->setBody($this->getHtmlBody(), 'text/html');

        if ($txt = $this->getTextBody()) {
            $msg->addPart($msg, 'text/html');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setFrom($from)
    {
        $this->from_email = $form;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFrom()
    {
        return $this->from_email;
    }

    /**
     * {@inheritdoc}
     */
    public function setContext(array $ctx)
    {
        $this->context = $ctx;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * {@inheritdoc}
     */
    public function setTwig(\Twig_Environment $twig)
    {
        $this->twig = $twig;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTwig()
    {
        return $this->twig;
    }

    /** ArrayAccess **********/

    public function offsetExists($key)
    {
        return array_key_exists($key, $this->context);
    }

    public function offsetUnset($key)
    {
        if (array_key_exists($key, $this->context)) {
            unset($this->context[$key]);
        }
    }

    public function offsetSet($key, $val)
    {
        $this->context[$key] = $val;
    }

    public function offsetGet($key)
    {
        return array_key_exists($key, $this->context) ? $this->context[$key] : null;
    }

    /** Utilities & Helpers **********/

    /**
     * Get the plain text email body.
     *
     * @since   0.1
     * @access  protected
     * @return  string|null
     */
    protected function getTextBody()
    {
        return null;
    }

    /**
     * Get the subject line of the email.
     *
     * @since   0.1
     * @access  protected
     * @return  string
     */
    abstract protected function getSubject();

    /**
     * Get the HTML email body.
     *
     * @since   0.1
     * @access  protected
     * @return  string
     */
    abstract protected function getHtmlBody();
}
