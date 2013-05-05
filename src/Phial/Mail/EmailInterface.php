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
 * Abstraction on how we interact with Swift Mailer
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
interface EmailInterface
{
    /**
     * Given a swift message, build it and prepare it to be sent.
     *
     * @since   0.1
     * @access  public
     * @param   Swift_Message $msg
     * @return  void
     */
    public function buildEmail(\Swift_Message $msg);

    /**
     * Set the from email.
     *
     * @since   0.1
     * @access  public
     * @param   string $from
     * @return  $this
     */
    public function setFrom($from);

    /**
     * Get the from email.
     *
     * @since   0.1
     * @access  public
     * @return  string
     */
    public function getFrom();

    /**
     * Set the context for rendering the email.
     *
     * @since   0.1
     * @access  public
     * @param   array $ctx
     * @return  $this
     */
    public function setContext(array $ctx);

    /**
     * Get the context for rendering the email.
     *
     * @since   0.1
     * @access  public
     * @return  array
     */
    public function getContext();

    /**
     * Set the twig environment
     *
     * @since   0.1
     * @access  public
     * @param   Twig_Environment $twig
     * @return  void
     */
    public function setTwig(\Twig_Environment $twig);

    /**
     * Get the twig environment.
     *
     * @since   0.1
     * @access  public
     * @return  Twig_Environment
     */
    public function getTwig();
}
