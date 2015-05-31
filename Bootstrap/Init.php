<?php
namespace Bootstrap;

use Application\AppStart\RoutesConfig;
use Chinook\Ioc\IocContainer;
use Config\App;

class Init
{
    protected $ioc;

    public function __construct ( IocContainer $ioc )
    {
        $this->ioc = $ioc;
    }

    public function loadErrorHandler ( )
    {
        if ( App::$debug )
        {
            /*
            $whoops = new \Whoops\Run;
            $handler = new \Whoops\Handler\PrettyPageHandler();
            $handler->setResourcesPath ( __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Exceptions' . DIRECTORY_SEPARATOR . 'Whoops' );
            $whoops->pushHandler($handler);
            $whoops->register();*/
        }
    }

    public function loadServiceProviders ( )
    {
        foreach ( App::$services as $service )
        {
            $provider = new $service ( $this->ioc );
            $provider->register ( );
        }
    }
}

?>