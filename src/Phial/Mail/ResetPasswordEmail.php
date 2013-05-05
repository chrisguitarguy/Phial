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
 * Email sent when a password reset is requested.
 *
 * Expected Context:
 *  - protocol (http, https)
 *  - domain
 *  - url
 *  - site_name
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class ResetPasswordEmail extends EmailBase
{
    /**
     * {@inheritdoc}
     */
    protected function getHtmlBody()
    {
        return $this->getTwig()->render('@email/reset_password.html.twig', $this->getContext());
    }

    /**
     * {@inheritdoc}
     */
    protected function getTextBody()
    {
        return $this->getTwig()->render('@email/reset_password.txt.twig', $this->getContext());
    }

    /**
     * {@inheritdoc}
     */
    protected function getSubject()
    {
        return 'Reset Your Password';
    }
}
