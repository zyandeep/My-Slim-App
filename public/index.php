<?php

##### The App's Router

require '../vendor/autoload.php';
require_once '../src/PeopleController.php';
require_once '../src/OfficeController.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

##### Application settings
$config = array();
$config['displayErrorDetails'] = true;                  // slim's default
$config['db'] = array(                                  // app specific
    'username' => 'zyandeep',
    'password' => '123qweASD',
    'dns' => 'oci:dbname=localhost/XE'
);


#### Instantiate the App object
$app = new \Slim\App(['settings' => $config]);


#### Get the default DIC
$container = $app->getContainer();

#### Add PDO service to the default DIC
$container['pdo'] = function ($c) {
    $db = $c->get('settings')['db'];
    $pdo = new PDO($db['dns'], $db['username'], $db['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};


#### Slim Error Handling

// add error handler service                    for Error : 500
$container['errorHandler'] = function ($c){
    return function ($request, $response, $exception) use ($c) {
        $data = array(
            'success' => false,
            //'msg' => 'Something went wrong in the server...'
            'msg' => $exception->getMessage()
        );

        return $response->withJson($data, 500);
    };
};

// add error handler service                    for Error : 404
$container['notFoundHandler'] = function ($c){
    return function ($request, $response) use ($c) {
        $data = array(
            'success' => false,
            'msg' => 'Page not found'
        );

        return $response->withJson($data, 404);
    };
};


// add error handler service                    for Error : 405
$container['notAllowedHandler'] = function ($c){
    return function ($request, $response, $arr) use ($c) {
        $data = array(
            'success' => false,
            'msg' => "HTTP method not allowed for this route. Try: $arr[0]"
        );

        return $response->withJson($data, 405);
    };
};

// add PHP 7 runtime error handler service                                
$container['phpErrorHandler'] = function ($c){
    return function ($request, $response, $error) use ($c) {
        $data = array(
            'success' => false,
            //'msg' => "PHP runtime error"
            'msg' => $error->getMessage()
        );

        return $response->withJson($data, 503);
    };
};


#### Register controllers with DIC

// get the default DIC
$container = $app->getContainer();

$container['PeopleController'] = function($c) {
    // retrieve the 'pdo' from the container
    $pdo = $c->get("pdo"); 
    return new PeopleController($pdo);
};

$container['OfficeController'] = function($c) {
    // retrieve the 'pdo' from the container
    $pdo = $c->get("pdo"); 
    return new OfficeController($pdo);
};




#### Declare the routes

$app->get('/hello[/{name}]', function ($request, $response, $args) {
    // check if the service exist in the DIC
    if ($this->has('pdo')) {
        $pdo = $this->get('pdo');           // get the service

        return $response->getBody()->write("db connected");
    }
})->add(function ($request, $response, $next) {
    // check if the route has 'name' path parameter

    $route = $request->getAttribute('route');
    $name = $route->getArgument('name');

    if (empty($name)) {
        $response = $response->withJson([
            'success' => false,
            'msg' => 'name parameter not found'
        ], 401);
    } 
    else {
        $response = $next($request, $response);
    }
    
    return $response;
});



// get all the people
//$app->get('/people', PeopleController::class . ':getPeople');
$app->get('/people', 'PeopleController:getPeople');

// get a single person
$app->get('/people/{id:[1-9]+}', 'PeopleController:getPeopleById');

// create a person
$app->post('/people/new', 'PeopleController:createPerson');


// get offices of #dept and #district
$app->get('/depts/{dept_code}/districts/{district_code}/offices', 'OfficeController:getOffices');



#### Finally, run the app
$app->run();