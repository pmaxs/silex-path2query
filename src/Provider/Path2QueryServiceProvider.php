<?php
namespace Pmaxs\Silex\Path2Query\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Pmaxs\Silex\Path2Query\EventListener\Path2QueryListener;

/**
 * Path2Query Provider.
 */
class Path2QueryServiceProvider implements ServiceProviderInterface
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
