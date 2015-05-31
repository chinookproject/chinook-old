<?php
namespace Core\Dispatchers;

use Chinook\Routing\RouteContext;
use Core\Exceptions\ViewNotFoundException;

class ViewRouteDispatcher
{
    public function dispatch ( RouteContext $route )
    {
        if ( !isset ( $route->params['view'] ) )
            return false;

        if ( !isset ( $route->params['area'] ) )
            $route->params['area'] = '';

        if ( $route->params['view'] !== '' )
            $route->view = $route->params['view'];

        $route->area = $route->params['area'];

        $viewPath = 'App' . DIRECTORY_SEPARATOR
            . ($route->area !== '' ? 'Areas' . DIRECTORY_SEPARATOR . $route->area . DIRECTORY_SEPARATOR : '' )
            . $route->viewBasePath;

        $viewFile = str_replace('/', DIRECTORY_SEPARATOR, $viewPath . DIRECTORY_SEPARATOR . $route->view );
        if ( is_dir ( $viewFile ) || !file_exists ( $viewFile ) )
        {
            throw new ViewNotFoundException ( 'Cant find view: ' . $viewFile );
        }

        $route->params['view'];
        $returnData = file_get_contents ( $viewFile );

        return $returnData;
    }
}

?>