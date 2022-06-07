<?php
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';
require_once './controllers/UsuarioController.php';


require_once "E:/xampp/htdocs/programacion_3/tp/app/models/JsonWebToken.php";
require_once "E:/xampp/htdocs/programacion_3/tp/app/controllers/MesaController.php";
require_once "E:/xampp/htdocs/programacion_3/tp/app/controllers/PedidoController.php";
require_once "E:/xampp/htdocs/programacion_3/tp/app/controllers/ProductoController.php";
require_once "E:/xampp/htdocs/programacion_3/tp/app/controllers/LoginController.php";
require_once "E:/xampp/htdocs/programacion_3/tp/app/middlewares/AutentificadorJWT.php";

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);




$app->get('[/]', function (Request $request, Response $response) {
  $response->getBody()->write("Trabajo practico Octavio Bill Zito 3°C");
  return $response;
});


$app->post('/login', \LoginController::class . ':iniciarSesion');

$app->group('/usuarios', function (RouteCollectorProxy $group) {
  $group->get('[/]', \UsuarioController::class . ':TraerTodos');
  $group->get('/{usuario}', \UsuarioController::class . ':TraerUno');
  $group->post('[/]', \UsuarioController::class . ':CargarUno');
})->add(\AutentificadorJWT::class . ':verificarToken');

$app->group('/mesas', function(RouteCollectorProxy $group)
{
  $group->get('[/]', \MesaController::class. ':TraerTodos');
  $group->get('/{mesa}', \MesaController::class. ':TraerUno');
  $group->post('[/]', \MesaController::class. ':CargarUno');
});

$app->group('/productos', function(RouteCollectorProxy $group)
{
  $group->get('[/]', \ProductoController::class. ':TraerTodos');
  $group->get('/{producto}', \ProductoController::class. ':TraerUno');
  $group->post('[/]', \ProductoController::class. ':CargarUno');
});

$app->group('/pedidos', function(RouteCollectorProxy $group)
{
  $group->get('[/]', \PedidoController::class. ':TraerTodos');
  $group->get('/{pedido}', \PedidoController::class. ':TraerUno');
  $group->post('[/]', \PedidoController::class. ':CargarUno');
});

$app->run();

?>
