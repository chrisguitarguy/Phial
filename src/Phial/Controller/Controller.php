<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Controller;

/**
 * Base controller class.
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
abstract class Controller
{
    /**
     * @since   0.1
     * @access  protected
     * @var     Twig_Environment
     */
    protected $twig = null;

    /**
     * @since   0.1
     * @access  protected
     * @var     Psr\Log\LoggerInterface
     */
    protected $logger = null;

    /**
     * @since   0.1
     * @access  protected
     * @var     Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $dispatcher = null;

    /**
     * @since   0.1
     * @access  protected
     * @var     Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @since   0.1
     * @access  protected
     * @var     Symfony\Component\Form\FormFactoryInterface
     */
    protected $forms;

    public function setTwig(\Twig_Environment $twig)
    {
        $this->twig = $twig;
        return $this;
    }

    public function getTwig()
    {
        return $this->twig;
    }

    public function setLogger(\Psr\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
        return $this;
    }

    public function getLogger()
    {
        return $this->logger;
    }

    public function setDispatcher(\Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        return $this;
    }

    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    public function setRequest(\Symfony\Component\HttpFoundation\Request $request)
    {
        $this->request = $request;
        return $this;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function setForms(\Symfony\Component\Form\FormFactoryInterface $forms)
    {
        $this->forms = $forms;
        return $this;
    }

    public function getForms()
    {
        return $this->forms;
    }

    protected function render($template, array $ctx=array())
    {
        $twig = $this->getTwig();

        if (!$twig) {
            throw new \RuntimeException('Twig_Environment is not set');
        }

        $request = $this->getRequest();
        $route = $request ? $request->attributes->get('_route') : null;

        $dispatcher = $this->getDispatcher();

        if ($route && $dispatcher) {
            $event = new \Phial\Event\GetTemplateEvent($route, $template);

            $dispatcher->dispatch(\Phial\PhialEvents::GET_TEMPLATE, $event);

            $template = $event->getTemplate();
        }

        return $twig->render($template, $ctx);
    }
}
