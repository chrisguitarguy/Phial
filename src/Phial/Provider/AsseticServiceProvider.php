<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Provider;

/**
 * Integration with assetic.
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class AsseticServiceProvider implements \Silex\ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(\Silex\Application $app)
    {
        $app['assetic.options'] = array(
            'debug'         => false,
            'web'           => null,
            'auto_dump'     => false,
        );

        $app['assetic.asset_manager'] = $app->share(function($app) {
            return new \Assetic\AssetManager();
        });

        $app['assetic.filter_manager'] = $app->share(function($app) {
            return new \Assetic\FilterManager();
        });

        $app['assetic.asset_writer'] = $app->share(function($app) {
            return new \Assetic\AssetWriter($app['assetic.options']['web']);
        });

        $app['assetic.twig_extension'] = $app->share(function($app) {
            return new \Assetic\Extension\Twig\AsseticExtension($app['assetic']);
        });

        $app['assetic'] = $app->share(function($app) {
            $factory = new \Assetic\Factory\AssetFactory(
                $app['assetic.options']['web'],
                $app['assetic.options']['debug']
            );

            $factory->setAssetManager($app['assetic.asset_manager']);
            $factory->setFilterManager($app['assetic.filter_manager']);

            return $factory;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function boot(\Silex\Application $app)
    {
        $app->after(function() use ($app) {
            if (!$app['assetic.options']['auto_dump']) {
                return;
            }

            $app['assetic.asset_writer']->writeManagerAssets($app['assetic.asset_manager']);
        });
    }
}
