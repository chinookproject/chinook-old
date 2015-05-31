<?php
namespace Config;

use Chinook\Routing\IRouteCollection;
use Chinook\Routing\ApiRoute;
use Chinook\Routing\Route;
use Chinook\Routing\ViewRoute;

class RoutesConfig
{
    protected $routes;

    public function __construct ( IRouteCollection $routes )
    {
        $this->routes = $routes;
    }

    public function configureRoutes ( )
    {
        $this->httpRoutes ( );
        $this->viewRoutes ( );
        $this->tplRoutes ( );
        $this->errorRoutes ( );
		
		return $this->routes;
    }
    
    protected function tplRoutes ( )
    {
        $this->routes->add ( new Route ( '{controller}/{action}/{id?}', 'Index', 'Index' ) );
    }
    
    protected function httpRoutes ( )
    {
    }

    protected function viewRoutes ( )
    {
        $this->routes->add ( new ViewRoute('cms/{view}', 'index.html', 'cms', '') );
    }
    
    protected function errorRoutes ( )
    {
        // In this example the system calls ErrorsController and the Http404Action(). 
        // When no custom error controller is specified, it will use the systems Error 
        // controller, located in Core/HttpErrors.
        
        // NOTE: The Route URL (first param in Route) must be formatted like this: _[error code]_
        // So: _404_ or _500_ etc.
        
        // Example
        //$this->routes->addRoute ( new CFTplRoute ( '_404_', 'Errors', 'Http404' ) );
    }

    public function getRoutes ( )
    {
        return $this->routes;
    }

}

?>