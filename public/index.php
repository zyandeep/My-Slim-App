<?php

##### The App's Router

require '../vendor/autoload.php';
require_once '../src/controller/PeopleController.php';
require_once '../src/controller/OfficeController.php';
require_once '../src/controller/AuthMiddleware.php';
require_once '../src/controller/PostDataController.php';
require_once '../src/controller/TransactionController.php';
require_once '../src/controller/GetDataController.php';
require_once '../src/controller/SubmitPaymentController.php';
require_once '../src/dao/SubmitPaymentDao.php';
require_once '../src/dao/GetDataDao.php';
require_once '../src/dao/TransactionDAO.php';


#### Declearing the classes with their namespaces
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Kreait\Firebase\Factory;                        
use Kreait\Firebase\ServiceAccount;
use Firebase\Auth\Token\Exception\InvalidToken;
use Symfony\Component\Cache\Simple\FilesystemCache;
use GuzzleHttp\Client;


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

#### Add services to the default DIC
$container['pdo'] = function ($c) {
    $db = $c->get('settings')['db'];
    $pdo = new PDO($db['dns'], $db['username'], $db['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

$container['firebase'] = function ($c) {
    // system cache to store google's public keys
    $cache = new FilesystemCache();                
    $serviceAccount = ServiceAccount::fromJsonFile('../serviceAccount.json');
    $firebase = (new Factory)
                ->withServiceAccount($serviceAccount)
                ->withVerifierCache($cache)
                ->create();

    return $firebase;
};

$container['guzzle'] = function ($c) {
    $client = new Client(['base_uri' => 'http://httpbin.org']);
    return $client;
};


#### Add AuthMiddleware 
$app->add(new AuthMiddleware($app->getContainer()->get('firebase')));



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

$container['TransactionController'] = function($c) {
    $pdo = $c->get("pdo"); 
    $dao = new TransactionDAO($pdo);
    return new TransactionController($dao);
};

$container['GetDataController'] = function($c) {
    $pdo = $c->get("pdo"); 
    $dao = new GetDataDao($pdo);
    return new GetDataController($dao);
};

$container['SubmitPaymentController'] = function($c) {
    $pdo = $c->get("pdo"); 
    $dao = new SubmitPaymentDao($pdo);
    return new SubmitPaymentController($dao);
};



#### Declare the routes
#### API endpoints

$app->get('/hello[/{name}]', function ($request, $response, $args) {
    // check if the service exist in the DIC
    if ($this->has('pdo')) {
        $pdo = $this->get('pdo');           // get the service

        return $response->getBody()->write("db connected");
    }
});

// get all the people
$app->get('/people', PeopleController::class . ':getPeople');
//$app->get('/people', 'PeopleController:getPeople');

// get a single person
$app->get('/people/{id:[1-9]+}', 'PeopleController:getPeopleById');

// create a person
$app->post('/people/new', 'PeopleController:createPerson');


// get offices of #dept and #district
$app->get('/depts/{dept_code}/districts/{district_code}/offices', 'OfficeController:getOffices');

// post data to httpbin
$app->post('/post', PostDataController::class);


#############################################################################################################
#### EGRAS ENDPOINTS

// get transactions
// GET:     /transactions?page=#
$app->get('/transactions', TransactionController::class . ':allTransactions');

// get departments
//GET:          /departments 
$app->get('/departments', GetDataController::class . ':departments');

// get payment types
// GET:         /paymenttypes
$app->get('/paymenttypes', GetDataController::class . ':paymenttypes');

// get districts for #dept_code
// GET:          /departments/{dept_code}/districts 
$app->get('/departments/{dept_code}/districts', GetDataController::class . ':districts');

// get offices for #dept_code & #district_code
// GET:          /departments/{dept_code}/districts/{district_code}/offices
$app->get('/departments/{dept_code}/districts/{district_code}/offices', GetDataController::class . ':offices');

// get schemes for #office_code 
// GET:         /offices/{office_code}/schemes
$app->get('/offices/{office_code}/schemes', GetDataController::class . ':schemes');

// post payment data 
// POST:         /submit-payment
$app->post('/submit-payment', SubmitPaymentController::class);

// get 5 recent transaction 
// GET:         /recent-transactions
$app->get('/recent-transactions', TransactionController::class . ':recentTransactions');


#### Finally, run the app
$app->run();