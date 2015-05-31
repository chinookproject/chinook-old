<?php
session_start();

error_reporting(E_ALL | E_STRICT);
ini_set("display_errors", 1);

unset ( $_GET['route'] );

require_once ( __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php' );
require_once ( __DIR__ . DIRECTORY_SEPARATOR . 'Bootstrap' . DIRECTORY_SEPARATOR . 'Init.php' );
require_once ( __DIR__ . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Main.php' );

use Chinook\Ioc\IocContainer;

$ioc = new IocContainer();
$bootstrap = $ioc->create('Bootstrap\Init');

$bootstrap->loadErrorHandler ( );
$bootstrap->loadServiceProviders ( );

$main = $ioc->create('Core\Main');
echo $main->run();

?>