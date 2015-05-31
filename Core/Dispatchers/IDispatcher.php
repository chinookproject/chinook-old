<?php
namespace Core\Dispatchers;

use Chinook\Routing\RouteContext;

interface IDispatcher
{
    function dispatch ( RouteContext $route );
}

?>