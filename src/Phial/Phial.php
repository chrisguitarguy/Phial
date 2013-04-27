<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial;

use Igorw\Silex\ConfigServiceProvider;

/**
 * The central application class.
 *
 * @since   0.1
 * @author  Christopher Davis
 */
class Phial extends \Silex\Application
{
    /**
     * The "application" root where we can find the view, cache, and config
     * directories.
     *
     * @since   0.1
     * @access  protected
     * @var     string
     */
    protected $root;

    /**
     * The application environment. Dev, production, etc.
     *
     * @since   0.1
     * @access  protected
     * @var     string
     */
    protected $env;

    public function __construct($root, $env='dev')
    {
        parent::__construct();

        $this->root = $root;
        $this->env = $env;

        if (in_array($this->env, array('dev', 'staging'))) {
            $this['debug'] = true;
        }

        $this->registerProviders();

        $this->loadConfig();
    }

    protected function registerProviders()
    {
        $this->register(new \Silex\Provider\ServiceControllerServiceProvider());

        $this->register(new \Silex\Provider\UrlGeneratorServiceProvider());

        $this->register(new \Silex\Provider\SwiftmailerServiceProvider());

        $this->register(new \Silex\Provider\MonologServiceProvider(), array(
            'monolog.level' => function($app) {
                return $app['debug'] ? \Monolog\Logger::DEBUG : \Monolog\Logger::WARNING;
            },
        ));

        $this->register(new \Silex\Provider\ValidatorServiceProvider());

        $this->register(new \Silex\Provider\TranslationServiceProvider());

        $this->register(new \Silex\Provider\FormServiceProvider());

        $this->register(new \Silex\Provider\TwigServiceProvider(), array(
            'twig.path'     => $this->pathJoin($this->root, 'views'),
            'twig.options'  => array(
                'cache'         => $this['debug'] ? $this->pathJoin($this->root, 'cache') : false,
            ),
        ));

        $this['twig'] = $this->share($this->extend('twig', function($twig, $app) {
            $twig->addGlobal('site_name', $app['site_name']);

            return $twig;
        }));

        $this['twig.loader.filesystem'] = $this->share(
            $this->extend('twig.loader.filesystem', function($loader, $app) {
                $loader->addPath($app['twig.path'].DIRECTORY_SEPARATOR.'admin', 'admin');
                $loader->addPath($app['twig.path'].DIRECTORY_SEPARATOR.'front', 'front');

                return $loader;
            })
        );

        $this->register(new \Silex\Provider\DoctrineServiceProvider());

        $this->register(new \Silex\Provider\SessionServiceProvider());
    }

    /**
     * Join paths with DIRECTORY_SEPARATOR
     *
     * @since   0.1
     * @access  protected
     * @return  string
     */
    protected function pathJoin()
    {
        return implode(DIRECTORY_SEPARATOR, func_get_args());
    }

    /**
     * Register some config service provider.
     *
     * @since   0.1
     * @access  protected
     * @return  void
     */
    protected function loadConfig()
    {
        $replacements = array(
            'app_root'      => $this->root,
        );

        $this->register(new ConfigServiceProvider(
            $this->pathJoin($this->root, 'config', 'config.yml'),
            $replacements
        ));

        $env_config = $this->pathJoin($this->root, 'config', $this->env . '.yml');
        if (file_exists($env_config)) {
            $this->register(new ConfigServiceProvider($env_config, $replacements));
        } elseif (file_exists($env_config . '.dist')) {
            $this->register(new ConfigServiceProvider($env_config . '.dist', $replacements));
        }
    }
}
