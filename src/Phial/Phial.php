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
use Phial\Storage\UserStorage;

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

        $this->registerSchemas();

        $this['current_user'] = $this->share(function($app) {
            if (
                ($user_id = $app['session']->get('user_id')) &&
                ($user = $app['users']->getById($user_id))
            ) {
                return $user;
            } else {
                return new Entity\AnonymousUser();
            }
        });

        $this['users_class'] = __NAMESPACE__ . '\\Storage\\UserStorage';
        $this['user_entity_class'] = __NAMESPACE__ . '\\Entity\\User';
        $this['users'] = $this->share(function($app) {
            return new $app['users_class']($app['db'], $app['user_entity_class']);
        });
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
            'twig.path'     => $this->pathJoin(__DIR__, 'Resources', 'views'),
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
                $view_dir = __DIR__.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'views';

                $loader->addPath($view_dir.DIRECTORY_SEPARATOR.'admin', 'admin');
                $loader->addPath($view_dir.DIRECTORY_SEPARATOR.'front', 'front');

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

        // load the default config
        $this->register(new ConfigServiceProvider(
            $this->pathJoin(__DIR__, 'Resources', 'config', 'config.yml'),
            $replacements
        ));

        // allow users to have their own configs...
        $main_config = $this->pathJoin($this->root, 'config', 'config.yml');
        if (file_exists($main_config)) {
            $this->register(new ConfigServiceProvider(
                $this->pathJoin($this->root, 'config', 'config.yml'),
                $replacements
            ));
        }

        // and an environment specific config as well
        $env_config = $this->pathJoin($this->root, 'config', $this->env . '.yml');
        if (file_exists($env_config)) {
            $this->register(new ConfigServiceProvider($env_config, $replacements));
        } elseif (file_exists($env_config . '.dist')) {
            $this->register(new ConfigServiceProvider($env_config . '.dist', $replacements));
        }
    }

    /**
     * Deal with all of the db schema things.
     *
     * @since   0.1
     * @access  protected
     * @return  void
     */
    protected function registerSchemas()
    {
        $this['user_schema_class'] = __NAMESPACE__ . '\\Schema\\UserSchema';
        $this['user_schema'] = $this->share(function($app) {
            return new Schema\UserSchema(
                UserStorage::USER_TABLE,
                UserStorage::CAP_TABLE,
                UserStorage::USER_CAPS
            );
        });

        $this['schema_manager'] = $this->share(function($app) {
            $manager = new Schema\SchemaManager();

            $manager->addSchema('users', $app['user_schema']);

            return $manager;
        });
    }
}
