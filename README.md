# Simple PHP Router

A real simple PHP Router for PHP 7.1+ inspired on [PHP Router](https://github.com/dannyvankooten/PHP-Router) and [Klein.php](https://github.com/klein/klein.php)

## Features

- Pre defined regular expression routing.
- Flexible to add new custom regular expressions.
- Use URL segments as parameters.
- Get request data and processes as parameters.
- Pass parameters to the controller.


## Usage

### Router in action
In order to have the Router working, you just need to instantiate the Router and add all your Routes to it.
```php
require __DIR__ . '/vendor/autoload.php';

use SimplePHPRouter\Router;
use SimplePHPRouter\Route;

// Load routes from a file.
$router = new Router();

// Add routes.
$router->addRoute('/', [Controllers\Controller::class, 'myAction']);
$router->addRoute('/news/[s:slug]', [Controllers\Controller::class, 'myAction']);
$router->addRoute('/news/[i:id]', [Controllers\Controller::class, 'myAction'], 'POST');

// Execute router.
$r = $router->run();
```

In case the request match with a route, the `run()` method will return the Route object and execute the callback defined on the Route.

### Load routes from a yaml file
In some cases, is better for maintenance and scalability to keep routes outside the code, so this is a method to initialize the Router with a set of rules defined on a yaml file.

```php
require __DIR__ . '/vendor/autoload.php';

use SimplePHPRouter\Router;
use SimplePHPRouter\Route;

// Load routes from a file.
$router = Router::loadFromFile('routes.yaml');

// Get controller
$controller = new Controller();

// You can even add more routes
$router->addRoute('/another/[s:slug]', [Controllers\Controller::class, 'myAction']);

// Execute router.
$r = $router->run();
```

The format for the yaml file should be as follow
```yaml
routes:
  - [/index, someClass, indexAction, GET]
  - [/contact, someClass, contactAction, POST]
  - [/about, someClass, aboutAction, GET]
```

### Controllers in action

The controller classes can be on any namespace and be build as you need.  
The Router have the ability to pass to the controller methods the Request object.

```php
namespace Controllers;

use SimplePHPRouter\Request;

class Controller
{
    public function myAction(Request $req)
    {
        // include here all your logic according the route matched.
    }
}
```
