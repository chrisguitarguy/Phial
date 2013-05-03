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
            $am = $factory->getAssetManager();

            $dir = dirname(__DIR__);

            $jquery = $factory->createAsset(array(
                $dir.'/Resources/static/vendor/jquery.min.js',
            ), array(), array(
                'name'      => 'jquery',
                'output'    => 'js/*.js',
            ));
            $am->set('jquery', $jquery);

            $gumby_css = $factory->createAsset(array(
                $dir.'/Resources/static/vendor/gumby/gumby.css',
            ), array(), array(
                'name'      => 'gumby',
                'output'    => 'css/*.css',
            ));
            $am->set('gumby_css', $gumby_css);

            $gumby_js = $factory->createAsset(array(
                $dir.'/Resources/static/vendor/gumby/gumby.min.js',
            ), array(), array(
                'name'      => 'gumby',
                'output'    => 'js/*.js',
            ));
            $am->set('gumby_js', $gumby_js);

            $modernizr = $factory->createAsset(array(
                $dir.'/Resources/static/vendor/modernizr/modernizr-2.6.2.min.js',
            ), array(), array(
                'name'      => 'modernizr',
                'output'    => 'js/*.js',
            ));
            $am->set('modernizr', $modernizr);

            $admin_css = $factory->createAsset(array(
                '@gumby_css',
                $dir.'/Resources/static/admin/css/*.css',
            ), array(), array(
                'name'      => 'phial_admin_css',
                'output'    => 'css/admin.css',
            ));
            $am->set('phial_admin_css', $admin_css);

            $admin_js = $factory->createAsset(array(
                '@jquery',
                '@modernizr',
                '@gumby_js',
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
