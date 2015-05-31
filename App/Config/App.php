<?php
namespace Config;

class App
{
    // Set this to false in production
    public static $debug = true;

    // The default language
    public static $defaultLanguage = 'en-us';

    public static $databaseConfig = array (
        'driver' => 'mysql',
        'database' => 'your_db',
        'host' => 'localhost',
        'username' => 'root',
        'password' => 'root'
    );

    // Default services that come with the framework
    public static $services = array (
        'Config\ServiceConfig',
        'Chinook\Routing\RoutingServiceProvider',
        'Chinook\Http\HttpServiceProvider',
        'Chinook\View\ViewServiceProvider'
    );
}

?>