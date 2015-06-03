<?php
namespace Core\Dispatchers;

use Core\Exceptions\ActionNotFoundException;
use Core\Exceptions\ControllerNotFoundException;
use Chinook\Ioc\IocContainer;
use Chinook\Routing\RouteContext;

class RouteDispatcher implements IDispatcher
{
    private $ioc;

    public function __construct ( IocContainer $ioc )
    {
        $this->ioc = $ioc;
    }

    public function dispatch ( RouteContext $route )
    {
        if ( !isset ( $route->params['controller'] ) )
            throw new ControllerNotFoundException ( );
        elseif ( !isset ( $route->params['action'] ) )
            throw new ActionNotFoundException ( );

        if ( !isset ( $route->params['area'] ) )
            $route->params['area'] = '';

        // Remove controller/action/area params, we don't need to pass those to the controller later on
        unset ( $route->params['controller'] );
        unset ( $route->params['action'] );
        unset ( $route->params['area'] );

        $controllerName = ucfirst ( $route->controller . 'Controller' );
        $actionName = lcfirst ( $route->action . 'Action' );

        $controllerNamespace = ($route->area !== null ? 'Areas\\' .  $route->area . '\\' : '' )  . 'Controllers\\';

        try
        {
            $controller = $this->ioc->create ( $controllerNamespace . $controllerName );
        }
        catch ( \Exception $ex )
        {
            throw new ControllerNotFoundException ( $controllerName );
        }

        if ( !method_exists ( $controller, $actionName ) )
            throw new ActionNotFoundException ( );

        $result = $controller->executeBeforeFilters ( $this->ioc, $controller, $actionName );
        if ( $result !== null ) {
            return $result;
        }

        $reflectionMethod = new \ReflectionMethod ( $controller, $actionName );
        $returnData = $reflectionMethod->invokeArgs ( $controller, $route->params );
        $result = $controller->executeAfterFilters ( $this->ioc, $controller, $actionName );
        if ( $result !== null )
            return $result;

        return $returnData;
    }
}

?>