<?php declare(strict_types = 1);

namespace DaMe\Pimple;

use DaMe\DaMe;
use DaMe\Loader;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class DaMeServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['da_me.data_path'] = null;

        $app['da_me.loader'] = function ($app) {
            return new Loader();
        };

        $app['da_me'] = function ($app) {
            return new DaMe($app['da_me.loader'], $app['da_me.data_path']);
        };
    }
}