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
     * @var     \Phial\Phial
     */
    protected $app = null;

    public function setApplication(\Phial\Phial $app)
    {
        $this->app = $app;
        return $this;
    }

    public function getApplication()
    {
        return $this->app;
    }

    protected function render($template, array $ctx=array())
    {
        $twig = $this->app['twig'];

        $request = false;
        try {
            $request = $this->app['request'];
        } catch (\Exception $e) {
            // pass
        }

        $route = $request ? $request->attributes->get('_route') : null;

        if ($route) {
            $event = new \Phial\Event\GetTemplateEvent($route, $template);

            $this->app['dispatcher']->dispatch(\Phial\PhialEvents::GET_TEMPLATE, $event);

            $template = $event->getTemplate();
        }

        return $twig->render($template, $ctx);
    }

    public function url($route, array $ctx=array())
    {
        return $this->app['url_generator']->generate($route, $ctx);
    }

    protected function flash($code, $msg)
    {
        $this->app['session']->getFlashBag()->add($code, $msg);
    }
}
