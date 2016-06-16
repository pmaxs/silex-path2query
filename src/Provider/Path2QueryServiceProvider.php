<?php
namespace Pmaxs\Silex\Path2Query\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Application;
use Silex\Api\BootableProviderInterface;
use Pmaxs\Silex\Path2Query\EventListener\Path2QueryListener;

/**
 * Path2Query Provider.
 */
class Path2QueryServiceProvider implements ServiceProviderInterface, BootableProviderInterface
{
    /**
     * @inheritdoc
     */
    public function register(Container $app)
    {
    }

    /**
     * @inheritdoc
     */
    public function boot(Application $app)
    {
        $app['dispatcher']->addSubscriber(new Path2QueryListener($app['routes']));
    }
}
