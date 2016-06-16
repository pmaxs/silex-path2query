<?php
namespace Pmaxs\Silex\Path2Query\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listener to resolve locale from path or host
 */
class Path2QueryListener implements EventSubscriberInterface
{
    /**
     * Query param name
     */
    const QUERY_PARAM = '__path2query__';

    /**
     * Route collection
     * @var RouteCollection
     */
    protected $routes;

    /**
     * Constructor
     * @param RouteCollection $routes routes
     */
    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(
                array('setupRoutes', 33),
                array('resolveQuery', 0),
            ),
        );
    }

    /**
     * Setups routes, adds query parameter
     * @param GetResponseEvent $event
     */
    public function setupRoutes(GetResponseEvent $event)
    {
        if ($event->getRequestType() != HttpKernelInterface::MASTER_REQUEST) return;

        foreach ($this->routes as $routeName => $route) {
            if (\substr($routeName, 0, 1) === '_') continue;
            if (\trim($route->getPath(), '/') == '') continue;

            $route
                ->setPath(\rtrim($route->getPath(), '/') . '{' . self::QUERY_PARAM . '}')
                ->setRequirement(self::QUERY_PARAM, '(?:/.*)?')
                ->setDefault(self::QUERY_PARAM, '')
            ;
        }
    }

    /**
     * Resolves query from path
     * @param GetResponseEvent $event
     */
    public function resolveQuery(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (($query = $request->get(self::QUERY_PARAM))) {
            $requestQuery = $request->query;

            $query = \explode('/', \trim($query, '/'));

            for ($i = 0; $i < \count($query) - 1; $i += 2) {
                if (isset($query[$i])) $param = \urldecode($query[$i]);
                else $param = null;

                if (isset($query[$i + 1])) $value = \urldecode($query[$i + 1]);
                else $value = null;

                if (!$requestQuery->has($param)) {
                    $_REQUEST[$param] = $value;
                    $_GET[$param] = $value;
                    $requestQuery->set($param, $value);
                }
            }
        }
    }
}
