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
 * Static resources have the potential to get big, let them have their own
 * area.
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class StaticResourceProvider implements \Silex\ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(\Silex\Application $app)
    {
        if (!isset($app['assetic'])) {
            return;
        }

        $app['assetic'] = $app->share($app->extend('assetic', function($factory, $app) {
            $factory->addWorker(new \Assetic\Factory\Worker\CacheBustingWorker());

            $am = $factory->getAssetManager();

            $dir = dirname(__DIR__);

            $jquery = $factory->createAsset(array(
                'http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js',
            ), array(), array(
                'name'      => 'jquery',
                'output'    => 'js/*.js',
            ));
            $am->set('jquery', $jquery);

            $normalize = $factory->createAsset(array(
                $dir . '/Resources/static/css/normalize.css',
            ), array(), array(
                'name'      => 'normalize',
                'output'    => 'css/*.css',
            ));
            $am->set('normalize', $normalize);

            $admin_css = $factory->createAsset(array(
                '@normalize',
                $dir.'/Resources/static/admin/css/*.css',
            ), array(), array(
                'name'      => 'phial_admin_css',
                'output'    => 'css/admin.css',
            ));
            $am->set('phial_admin_css', $admin_css);

            $admin_js = $factory->createAsset(array(
                '@jquery',
                $dir.'/Resources/static/admin/js/*.js',
            ), array(), array(
                'name'      => 'phial_admin_js',
                'output'    => 'js/admin.js',
            ));
            $am->set('phial_admin_js', $admin_js);

            return $factory;
        }));
    }

    /**
     * {@inheritdoc}
     */
    public function boot(\Silex\Application $app)
    {
        // empty
    }
}
