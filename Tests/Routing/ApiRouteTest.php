<?php
use Chinook\TestSuite\Mock\Mock;
use Chinook\TestSuite\Unit\UnitTestCase;

class ApiRouteTest extends UnitTestCase
{
    private $ioc;
    public function setUpBeforeClass()
    {
        $this->ioc = new Chinook\Ioc\IocContainer();
    }

    public function test_api_route_with_default_controller_should_have_property()
    {
        $apiRoute = new Chinook\Routing\ApiRoute('{controller}/{id?}', 'User');
        $this->assert($apiRoute->routePattern)->should()->notBe(null);
        $this->assert($apiRoute->controller)->should()->be('User');
    }
}

?>