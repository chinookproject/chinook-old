<?php
use Chinook\TestSuite\Mock\Mock;
use Chinook\TestSuite\Unit\UnitTestCase;

class RandomStringGeneratorTest extends UnitTestCase
{
    private $ioc;
    public function setUpBeforeClass()
    {
        $this->ioc = new Chinook\Ioc\IocContainer();
    }

    public function test_random_string_with_default_settings_should_return_random_string_with_given_length()
    {
        $length = 10;
        $generator = new \Chinook\Security\RandomStringGenerator();

        $randomString = $generator->generate($length);

        $this->assert($randomString)->should()->notBeEmpty()->and()->haveLength($length);
    }

    public function test_random_string_with_custom_alphabet_should_return_random_string_with_no_other_chars_than_specified()
    {
        $length = 1;
        $generator = new \Chinook\Security\RandomStringGenerator('abc');

        $randomString = $generator->generate($length);

        $result = function() use ($randomString) {
            if ( preg_match ( '#[abc]#', $randomString ) )
                return true;

            return false;
        };
        $this->assert($result())->should()->beTrue();
    }
}

?>