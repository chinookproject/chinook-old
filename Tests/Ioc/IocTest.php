<?php
use Chinook\TestSuite\Mock\Mock;
use Chinook\TestSuite\Unit\UnitTestCase;

class IocTest extends UnitTestCase
{
    public function test_ioc_should_create_instance_of_given_class()
    {
        $expect = 'created';
        $ioc = new Chinook\Ioc\IocContainer();

        $obj = $ioc->create('Foo');
        $result = $obj->test();

        $this->assert($result)->should()->be($expect);
    }

    public function test_ioc_should_resolve_classes_based_on_param_type_in_ctor()
    {
        $expect = 'created';
        $ioc = new Chinook\Ioc\IocContainer();

        $obj = $ioc->create('Bar');
        $result = $obj->foo->test();

        $this->assert($result)->should()->be($expect);
    }

    public function test_ioc_bind_should_return_custom_data()
    {
        $expect = 'set';
        $ioc = new Chinook\Ioc\IocContainer();

        $ioc->bind('Foo', function() {
            $cls = new Foo();
            $cls->custom = 'set';
            return $cls;
        });
        $obj = $ioc->create('Foo');

        $result = $obj->custom;

        $this->assert($result)->should()->be($expect);
    }
}

class Foo
{
    public $custom = 'not set';
    public function __construct() {}

    public function test()
    {
        return 'created';
    }
}

class Bar
{
    public $foo;
    public function __construct(Foo $foo)
    {
        $this->foo = $foo;
    }
}



?>