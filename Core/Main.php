<?php
namespace Core;

use Chinook\Http\HttpStatus;
use Chinook\Ioc\IocContainer;
use Chinook\Routing\IRouteCollection;
use Chinook\Routing\RouteCollection;
use Chinook\Routing\Router;
use Config\App;
use Config\RoutesConfig;
use Core\Exceptions\ActionNotFoundException;
use Core\Exceptions\ControllerNotFoundException;
use Core\Exceptions\ViewNotFoundException;

class Main
{
    protected $ioc;
    protected $request;
    protected $response;

    /**
     * @SplFileInfo Object
     */
    protected $routeConfigs = array ( );
    protected $route;

    public function __construct ( IocContainer $ioc )
    {
        $this->ioc = $ioc;
    }

    public function run ( )
    {
        $this->request = $this->ioc->create ( 'Chinook\Http\Request' );
        $this->response = $this->ioc->create ( 'Chinook\Http\Response' );

        $routeCollection = $this->initAllRouteConfigs ( );
        $this->resolveRouteAndDispatch ( $routeCollection );

        $data = $this->response->send ( ) !== null ? $this->response->send ( ) : '';

        if ( $this->response->getContentType() == 'application/json' ) {
            if ( !is_scalar ( $data ) || is_array ( $data ) ) {
                return json_encode ( $this->response->send ( ) );
            }
        }

        return $this->response->send ( );
    }

    protected function resolveRouteAndDispatch ( IRouteCollection $routeCollection )
    {
        $route = null;

        $router = new Router ( $routeCollection, $this->ioc );

        while ( count ( $router->getRoutes()->count() ) > 0 )
        {
            $route = $router->findMatchingRoute ( $this->request->uri() );

            if ( $route !== null )
            {
                $dispatcher = $this->getDispatcher ( $route );
                if ( $dispatcher !== null )
                {
                    try
                    {
                        $returnData = $dispatcher->dispatch ( $route );

                        $this->response->setContent ( $returnData );

                        // return so that the response can be processed
                        return;
                    }
                    catch(\Exception $ex)
                    {
                        if ( $ex instanceof ControllerNotFoundException OR
                            $ex instanceof ActionNotFoundException OR
                            $ex instanceof ViewNotFoundException)
                        {
                            $requestedUrl = '_404_';
                        }
                        else
                        {
                            throw $ex;
                        }
                    }
                }
            }
            else
            {
                break;
            }
        }

        if ( $this->request->uri() === '_404_' )
        {
            $this->resolveRouteAndDispatch ( '_404_' );
        }
        else
        {
            $httpErrorDirectory = __DIR__ . DIRECTORY_SEPARATOR . 'HttpErrors' . DIRECTORY_SEPARATOR;
            if ( file_exists ( $httpErrorDirectory . 'Error404.html' ) )
            {
                $this->response->setContent ( file_get_contents ( $httpErrorDirectory . 'Error404.html' ) );
                $this->response->setStatusCode ( HttpStatus::HTTP_NOT_FOUND_404 );
            }
            else
            {
                $this->response->setContent ( file_get_contents ( $httpErrorDirectory . 'Default.html' ) );
            }
        }
    }

    private function getDispatcher ( $route )
    {
        $dispatcherClass = 'Core\Dispatchers\\' . $route->typeOfRoute . 'Dispatcher';
        $dispatcher = $this->ioc->create ( $dispatcherClass );

        return $dispatcher;
    }

    private function initAllRouteConfigs ( )
    {
        // Configure default routes config
        $routeCollection = new RouteCollection ( );
        $RouteConfigs = new RoutesConfig ( $routeCollection );
        $routeCollection = $RouteConfigs->configureRoutes ( );

        // Find additional routes config files in areas
        $it = new \RecursiveDirectoryIterator(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'Areas' );
        foreach(new \RecursiveIteratorIterator($it) as $file)
        {
            if ( strtolower( $file->getFilename() ) !== 'routesconfig.php' )
                continue;

            $pathParts = explode ( 'Areas', $file->getPath() );
            $namespace = str_replace( DIRECTORY_SEPARATOR, '\\', 'Areas' . DIRECTORY_SEPARATOR . ltrim ( $pathParts[1], DIRECTORY_SEPARATOR ) );

            require_once ( $file->getPathname() );

            $class = new \ReflectionClass( $namespace . '\\RoutesConfig' );
            $instance = $class->newInstanceArgs ( array ( $routeCollection ) );
            $routeCollection = $instance->configureRoutes ( $routeCollection );
        }

        return $routeCollection;
    }

}