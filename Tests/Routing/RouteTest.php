<?php
use Chinook\TestSuite\Mock\Mock;
use Chinook\TestSuite\Unit\UnitTestCase;

class RouteTest extends UnitTestCase
{
    private $ioc;
    public function setUpBeforeClass()
    {
        $this->ioc = new Chinook\Ioc\IocContainer();
    }

    public function test_route_with_default_controller_and_action_should_have_properties()
    {
        $apiRoute = new Chinook\Routing\Route('{controller}/{action}/{id?}', 'Home', 'Index');
        $this->assert($apiRoute->routePattern)->should()->notBe(null);
        $this->assert($apiRoute->controller)->should()->be('Home');
        $this->assert($apiRoute->action)->should()->be('Index');
    }
}

?>