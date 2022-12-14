<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Tuupola\Middleware\HttpBasicAuthentication;
use \Firebase\JWT\JWT;
require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/models/client.php';
require_once __DIR__ . '/models/avion.php';

 
const JWT_SECRET = "makey1234567";

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
    // you want to allow, and if so:
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
    
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        // may also be using PUT, PATCH, HEAD etc
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

function createJWT(Response $response): Response{
    $issuedAt   = new DateTimeImmutable();
    $expire     = $issuedAt->modify('+6 minutes')->getTimestamp();      // Ajoute 60 secondes
    $serverName = "your.domain.name";
    $username   = "username";                                           // Récupéré à partir des données POST filtré

    $data = [
        'iat'  => $issuedAt->getTimestamp(),         // Issued at:  : heure à laquelle le jeton a été généré
        'iss'  => $serverName,                       // Émetteur
        'nbf'  => $issuedAt->getTimestamp(),         // Pas avant..
        'exp'  => $expire,                           // Expiration
        'userName' => $username,                     // Nom d'utilisateur
    ];
    $jeton =JWT::encode(
        $data,
        JWT_SECRET,
        'HS512'
    );
    return $response;
}

function  addHeaders (Response $response) : Response {
    $response = $response
    ->withHeader("Content-Type", "application/json")
    ->withHeader('Access-Control-Allow-Origin', ('https://tp06-cnam-web.onrender.com'))
    ->withHeader('Access-Control-Allow-Headers', 'Content-Type,  Authorization')
    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
    ->withHeader('Access-Control-Expose-Headers', 'Authorization');

    return $response;
}

// $app->get('/api/planes/getAll', function (Request $request, Response $response, $args) {
//     $inputJSON = file_get_contents('./mockup/catalogue.json');
//     $response->getBody()->write($inputJSON);
//     return $response;
// });

$app->get('/api/planes/getAll', function (Request $request, Response $response, $args) {
    global $entityManager;
    $products = $entityManager->getRepository('avion')->findAll();
    $response = addHeaders($response);
    $response->getBody()->write(json_encode ($products));
    return $response;
});

$app->get('/api/clients/getAll', function (Request $request, Response $response, $args) {
    global $entityManager;
    $clients = $entityManager->getRepository('client')->findAll();
    $response = addHeaders($response);
    $response->getBody()->write(json_encode ($clients));
    return $response;
});

$app->get('/api/clients/getAll/{id}', function (Request $request, Response $response, $args) {
    global $entityManager;
    $client = $entityManager->getRepository('client')->findOneBy(array('id' => $args['id']));
    $response = addHeaders($response);
    $response->getBody()->write(json_encode ($client));
    return $response;
});

$app->post('/api/getLogin', function (Request $request, Response $response, $args) {
    $logged=false;
    $inputJSON = file_get_contents('php://input');
    $body = json_decode( $inputJSON, TRUE );
    $login = $body['log'] ?? ""; 
    $password = $body['mdp'] ?? "";

    if (empty($login) || empty($password)) {
        $logged=true;
    }
 
    if (!$logged) {
        $response = createJwT($response);
        $response->getBody()->write(json_encode(array('login' => $login)));
    }
    else{          
        $response = $response->withStatus(401);
    }
    return $response;
   
});
    
 

$options = [
    "attribute" => "token",
    "header" => "Authorization",
    "regexp" => "/Bearer\s+(.*)$/i",
    "secure" => false,
    "algorithm" => ["HS256"],
    "secret" => JWT_SECRET,
    "path" => ["/api"],
    "ignore" => ["/api/planes/getAll","/api/getLogin","/api/clients/getAll"],
    "error" => function ($response, $arguments) {
        $data = array('ERREUR' => 'Connexion', 'ERREUR' => 'JWT Non valide');
        $response = $response->withStatus(401);
        return $response->withHeader("Content-Type", "application/json")->getBody()->write(json_encode($data));
    }
];

$app->add(new Tuupola\Middleware\JwtAuthentication($options));
$app->run ();
