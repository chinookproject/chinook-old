chinook framework
=======

This readme is far f rom complete. This framework is currently used for custom projects. It currently comes with the entire set of "vendor" classes installed by composer.
The framework will be split into pieces when we think it's ready for that.


But, feel free to download it and to try it out.

To get started simply clone the repo into a wwwroot or some sub folder in your wwwroot.

(* Optional)
Open the *App/Config /App.php" file to setup your Database connection settings

Open up a browser and navigate to the following URL:

http://example.com/index

This should trigger the *App/Controllers/IndexController* class and the "indexAction" method.

Routes
=====

Routes can be configured in "App/Config/Routes.php"

IoC
===
Simply add params to the constructor of your controller to inject the classes that you need. For example:

``` 
<?php

use Chinook\Database\Orm;
use Chinook\Http\Request;
use Chinook\Http\Response;

class SomeController
{
	public function __construct(Request $request, Response $response, Orm $db) {}	
}
```

Currently added to the framework:

 * Core classes like bootstrapping
 * Routing
  * Tpl routing (parse template)
  * Api routing (for RESTful routing)
  * View routing (not well tested - used to directly serve an HTML file)
 * Dispatcher
 * Controllers
 * ApiControllers
 * Database class (PDO)
  * ORM Class wrapped around the Database class
 * IoC - Inversion of Control supported
 * Http Response and Request classes
 * Filters - Classes/methods that will be executed before and after a controller class is executed (Much like .NET's filter classes)
 * Simple template engine
 * Areas - A place for module based Controllers/Models etc. (Like .NET's Areas folder)