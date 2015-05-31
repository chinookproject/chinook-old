<?php
namespace Config;

use Chinook\Database\Orm;
use Chinook\Ioc\ServiceProvider;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Class ServiceConfig
 * @package Config
 *
 * Example on how to register 3rd party services
 */
class ServiceConfig extends ServiceProvider
{
    public function register ( )
    {
        $this->ioc->bind ( 'Chinook\Database\Orm', function() {
            $orm = new Orm ( App::$databaseConfig['driver'],
                App::$databaseConfig['database'],
                App::$databaseConfig['host'],
                App::$databaseConfig['username'],
                App::$databaseConfig['password'] );
            return $orm;
        });

        $this->ioc->bind ( 'Monolog\Logger', function() {
            $log = new Logger('name');
            $log->pushHandler(new StreamHandler(__DIR__ . '/../Logs/debug.txt', Logger::DEBUG));
            return $log;
        });
    }
}