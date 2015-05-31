<?php
use Chinook\TestSuite\Mock\Mock;
use Chinook\TestSuite\Unit\UnitTestCase;

class RouterTest extends UnitTestCase
{
    private $ioc;
    public function setUpBeforeClass()
    {
        $this->ioc = new Chinook\Ioc\IocContainer();
    }

    public function test_tpl_route_with_default_controller_and_action_with_empty_url_should_be_mapped_to_default_route_properties()
    {
        $route = new Chinook\Routing\Route('{controller}/{action}/{id?}', 'Home', 'index');

        $routeCollectionMock = Mock::create( 'Chinook\Routing\IRouteCollection' );
        $routeCollectionMock->aCallTo('getAll')->returns( array ( $route ) );

        $router = new Chinook\Routing\Router($routeCollectionMock, $this->ioc);
        $routeContext = $router->findMatchingRoute('');

        $this->assert($routeContext->controller)->should()->be('Home');
        $this->assert($routeContext->action)->should()->be('index');
        $this->assert($routeContext->area)->should()->be(null);
    }

    public function test_tpl_route_with_default_controller_and_action_with_single_slash_as_url_should_be_mapped_to_default_route_properties()
    {
        $route = new Chinook\Routing\Route('{controller}/{action}/{id?}', 'Home', 'index');

        $routeCollectionMock = Mock::create( 'Chinook\Routing\IRouteCollection' );
        $routeCollectionMock->aCallTo('getAll')->returns( array ( $route ) );

        $router = new Chinook\Routing\Router($routeCollectionMock, $this->ioc);
        $routeContext = $router->findMatchingRoute('');

        $this->assert($routeContext->controller)->should()->be('Home');
        $this->assert($routeContext->action)->should()->be('index');
        $this->assert($routeContext->area)->should()->be(null);
    }

    public function test_tpl_route_with_default_controller_and_action_should_be_mapped_to_corresponding_properties()
    {
        $route = new Chinook\Routing\Route('{controller}/{action}/{id?}', 'Home', 'index');

        $routeCollectionMock = Mock::create( 'Chinook\Routing\IRouteCollection' );
        $routeCollectionMock->aCallTo('getAll')->returns( array ( $route ) );

        $router = new Chinook\Routing\Router($routeCollectionMock, $this->ioc);
        $routeContext = $router->findMatchingRoute('home/index');

        $this->assert($routeContext->controller)->should()->be('home');
        $this->assert($routeContext->action)->should()->be('index');
        $this->assert($routeContext->area)->should()->be(null);
    }

    public function test_tpl_route_with_default_controller_and_action_with_prefix_url_should_be_mapped_to_corresponding_properties()
    {
        $route = new Chinook\Routing\Route('prefix/{controller}/{action}/{id?}', 'Home', 'index');

        $routeCollectionMock = Mock::create( 'Chinook\Routing\IRouteCollection' );
        $routeCollectionMock->aCallTo('getAll')->returns( array ( $route ) );

        $router = new Chinook\Routing\Router ( $routeCollectionMock, $this->ioc );
        $routeContext = $router->findMatchingRoute('prefix/home/index');

        $this->assert($routeContext->controller)->should()->be('home');
        $this->assert($routeContext->action)->should()->be('index');
        $this->assert($routeContext->area)->should()->be(null);
    }


    public function test_api_route_with_default_controller_should_be_mapped_to_corresponding_properties()
    {
        $route = new Chinook\Routing\ApiRoute('{controller}/{id?}', 'Home');

        $routeCollectionMock = Mock::create( 'Chinook\Routing\IRouteCollection' );
        $routeCollectionMock->aCallTo('getAll')->returns( array ( $route ) );

        $router = new Chinook\Routing\Router ( $routeCollectionMock, $this->ioc );
        $routeContext = $router->findMatchingRoute('home');

        $this->assert($routeContext->controller)->should()->be('home');
        $this->assert($routeContext->area)->should()->be(null);
    }

    public function test_api_route_with_default_controller_and_area_should_be_mapped_to_corresponding_properties()
    {
        $route = new Chinook\Routing\ApiRoute('{controller}/{id?}', 'Home', 'Area');

        $routeCollectionMock = Mock::create( 'Chinook\Routing\IRouteCollection' );
        $routeCollectionMock->aCallTo('getAll')->returns( array ( $route ) );

        $router = new Chinook\Routing\Router ( $routeCollectionMock, $this->ioc );
        $routeContext = $router->findMatchingRoute('home');

        $this->assert($routeContext->controller)->should()->be('home');
        $this->assert($routeContext->area)->should()->be('Area');
    }


//    public function test_view_route_with_default_view_should_be_mapped_to_corresponding_properties()
//    {
//        $route = new Chinook\Routing\ViewRoute('{view}', 'index');
//
//        $routeCollectionMock = Mock::create( 'Chinook\Routing\IRouteCollection' );
//        $routeCollectionMock->aCallTo('getAll')->returns( array ( $route ) );
//
//        $router = new Chinook\Routing\Router($routeCollectionMock, $this->ioc);
//        $routeContext = $router->findMatchingRoute('index');
//
//        $this->assert($routeContext->view)->should()->be('index');
//        $this->assert($routeContext->area)->should()->be(null);
//    }
}

?>