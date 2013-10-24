<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Pavel
 * Date: 10.04.13
 * Time: 13:01
 * To change this template use File | Settings | File Templates.
 */

namespace Providers;

use Silex\Application;
use Silex\ServiceProviderInterface;

class ImagineServiceProvider implements ServiceProviderInterface {

    public function register(Application $app)
    {
        if(!isset($app['imagine.factory'])) {
            $app['imagine.factory'] = 'Gd';
        }

        $app['imagine'] = $app->share(function ($app) {
            $class = sprintf('\Imagine\%s\Imagine', $app['imagine.factory']);
            return new $class();
        });
    }

    public function boot(Application $app)
    {
    }
}