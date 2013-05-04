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
    const USER_TABLE    = 'phial_users';
    const CONTENT_TABLE = 'phial_content';

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

        $this['site_name'] = 'Phial';

        $this->registerProviders();

        $this->loadConfig();

        $this->registerSchemas();

        $this->registerControllers();

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
            return new $app['users_class'](
                $app['db'],
                $app['user_entity_class'],
                $app['user_table']
            );
        });

        $this['escaper_class'] = __NAMESPACE__ . '\\Escaper';
        $this['escaper'] = $this->share(function($app) {
            return new $app['escaper_class'];
        });

        $this['template_tag_ext'] = function($app) {
            return new Twig\TemplateTagExtension($app['escaper']);
        };

        $this['assetic_func_ext'] = function($app) {
            return new Twig\AsseticFunctionExtension(
                $app['assetic']->getAssetManager(),
                $app['escaper']
            );
        };
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

        $twig_cache = $this->pathJoin($this->root, 'cache');
        $this->register(new \Silex\Provider\TwigServiceProvider(), array(
            'twig.path'     => $this->pathJoin(__DIR__, 'Resources', 'views'),
            'twig.options'  => array(
                'cache'         => $this['debug'] && is_dir($twig_cache) ? $twig_cache : false,
            ),
        ));

        $this['twig'] = $this->share($this->extend('twig', function($twig, $app) {
            $twig->addGlobal('site_name', $app['site_name']);

            try {
                $current = $app['request']->attributes->get('_route');
            } catch (\Exception $e) {
                $current = '';
            }
            $twig->addGlobal('current_route', $current);

            $twig->addExtension($app['template_tag_ext']);
            $twig->addExtension($app['assetic_func_ext']);


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

        $this->register(new Provider\AsseticServiceProvider());

        $this->register(new Provider\StaticResourceProvider());
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
        $this['user_table'] = static::USER_TABLE;
        $this['user_schema_class'] = __NAMESPACE__ . '\\Schema\\UserSchema';
        $this['user_schema'] = $this->share(function($app) {
            return new Schema\UserSchema($app['user_table']);
        });

        $this['schema_manager'] = $this->share(function($app) {
            $manager = new Schema\SchemaManager();

            $manager->addSchema('users', $app['user_schema']);

            return $manager;
        });
    }

    /**
     * Register our controller services and controller providers.
     *
     * @since   0.1
     * @access  protected
     * @return  void
     */
    protected function registerControllers()
    {
        $app = $this;

        $this['init_controller'] = $this->protect(function(\Phial\Controller\Controller $c) use ($app) {
            $c->setTwig($app['twig'])
                ->setLogger($app['monolog'])
                ->setDispatcher($app['dispatcher'])
                ->setRequest($app['request'])
                ->setForms($app['form.factory']);

            return $c;
        });

        $this['controller.user_admin_class'] = 'Phial\\Controller\\UserAdmin';
        $this['controller.user_admin'] = function($app) {
            $c = new $app['controller.user_admin_class']($app['users']);

            return $app['init_controller']($c);
        };

        $this['controller.admin_class'] = 'Phial\\Controller\\Admin';
        $this['controller.admin'] = function($app) {
            $c = new $app['controller.admin_class']();

            return $app['init_controller']($c);
        };

        $this->mount('/admin', new Provider\UserAdminControllerProvider());
        $this->mount('/admin', new Provider\AdminControllerProvider());
    }
}
