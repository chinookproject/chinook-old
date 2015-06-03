<?php
namespace Core\Dispatchers;

use Chinook\Http\Response;
use Chinook\Ioc\IocContainer;
use Chinook\Routing\RouteContext;
use Core\Exceptions\ActionNotFoundException;
use Core\Exceptions\ControllerNotFoundException;
use Whoops\Example\Exception;

class ApiRouteDispatcher
{
    protected $ioc;
    private $response;

    public function __construct ( IocContainer $ioc, Response $response )
    {
        $this->ioc = $ioc;
        $this->response = $response;
    }

    public function dispatch ( RouteContext $route )
    {
        if ( !isset ( $route->params['controller'] ) )
            throw new ControllerNotFoundException ( );

        if ( !isset ( $route->params['area'] ) )
            $route->params['area'] = '';

        $route->controller = ucfirst ( $route->params['controller'] );
        $route->area = $route->params['area'];

        $controllerPath = 'App' . DIRECTORY_SEPARATOR
            . ($route->area !== '' ? 'Areas' . DIRECTORY_SEPARATOR . $route->area . DIRECTORY_SEPARATOR : '' )
            . 'Controllers';

        if ( !file_exists ( $controllerFile = $controllerPath . DIRECTORY_SEPARATOR . $route->controller.'Controller.php' ) )
        {
            throw new ControllerNotFoundException ( "Can't find API controller " . $controllerFile );
        }

        $controllerName = $route->controller . 'Controller';
        $controllerNamespace = ($route->area !== '' ?  'Areas\\' .  $route->area . '\\' : '' ) . 'Controllers\\';

        // Remove controller/action/area params, we don't need to pass those to the controller later on
        unset ( $route->params['controller'] );
        unset ( $route->params['area'] );

        // Resolve action
        $actionName = '';
        if ( isset ( $route->params['action'] ) )
        {
            $requestMethod = strtolower ( $_SERVER['REQUEST_METHOD'] );
            $actionName = $requestMethod . ucfirst ( $route->params['action'] );
            unset ( $route->params['action'] );
        }

        try
        {
            $controller = $this->ioc->create ( $controllerNamespace . $controllerName );
        }
        catch ( Exception $ex )
        {
            throw new ControllerNotFoundException ( "Can't find API controller " . $controllerFile );
        }

        if ( !method_exists ( $controller, $actionName ) )
        {
            $actionName = $this->resolveRestActionMethodFromController ( $controllerNamespace . $controllerName, $route );
            if ( $actionName === null )
            {
                throw new ActionNotFoundException ( "Failed to resolve action for controller " . $controllerName );
            }
        }

        $route->action = $actionName;

        // Set appropriate content type
        $this->response->setContentType('application/json');

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

    private function resolveRestActionMethodFromController ( $controllerClass, $route )
    {
        try
        {
            $controller = new \ReflectionClass ( $controllerClass );
            $methods = $controller->getMethods ( \ReflectionMethod::IS_PUBLIC );
            $requestMethod = strtolower ( $_SERVER['REQUEST_METHOD'] );
        }
        catch ( Exception $ex )
        {
            throw new ControllerNotFoundException ( "Can't find API controller " . $controllerClass );
        }

        // Filter methods array, so it only contains methods with the right amount of params.
        foreach ( $methods as $index => $method )
        {
            if ( count ( $route->params ) < $method->getNumberOfRequiredParameters() )
            {
                unset ( $methods[$index] );
            }

            if ( strpos ( $method->name, $requestMethod ) !== 0 )
            {
                unset ( $methods[$index] );
            }
        }

        if ( empty ( $methods ) )
        {
            return null;
        }

        // If we have multiple methods, then take the method with the right amount of params
        if ( count ( $methods ) > 1 )
        {
            foreach ( $methods as $index => $method )
            {
                if ( count ( $route->params ) === $method->getNumberOfParameters() )
                {
                    $methods = array ( $method );
                    break;
                }
            }
        }

        $method = array_shift ( $methods );
        return $method->name;
    }
}

?>