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
require_once './models/JsonWebToken.php';
require_once './middlewares/AutentificadorJWT.php';
require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/LoginController.php';
require_once './controllers/ClienteController.php';
require_once './controllers/EncuestaController.php';
require_once './controllers/FacturaController.php';
require_once './controllers/ListaController.php';
require_once './controllers/RegistroAccionesController.php';
require_once './controllers/RegistroIngresosController.php';
require_once './controllers/InformesController.php';

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
    $group->delete('/{usuario}', \UsuarioController::class . ':BorrarUno');
    $group->put('[/]', \UsuarioController::class . ':ModificarUno');
  })->add(\AutentificadorJWT::class . ':verificarToken')->add(\AutentificadorJWT::class . ':verificarRolSocio');

  $app->group('/mesas', function(RouteCollectorProxy $group)
  {
    $group->get('[/]', \MesaController::class. ':TraerTodos');
    $group->get('/{id}', \MesaController::class. ':TraerUno');
    $group->post('[/]', \MesaController::class. ':CargarUno')->add(\AutentificadorJWT::class . ':verificarRolSocio');
    $group->delete('/{numero_de_mesa}', \MesaController::class . ':BorrarUno')->add(\AutentificadorJWT::class . ':verificarRolSocio');
    $group->put('[/]', MesaController::class . ':ModificarUno')->add(\AutentificadorJWT::class . ':verificarRolSocio');
  })->add(\AutentificadorJWT::class . ':verificarToken');

  $app->group('/productos', function(RouteCollectorProxy $group)
  {
    $group->get('[/]', \ProductoController::class. ':TraerTodos');
    $group->get('/{id}', \ProductoController::class. ':TraerUno');
    $group->post('[/]', \ProductoController::class. ':CargarUno')->add(\AutentificadorJWT::class . ':verificarRolSocio');
    $group->delete('/{id}', \Producto::class . ':BorrarUno')->add(\AutentificadorJWT::class . ':verificarRolSocio');
    $group->put('[/]', \Producto::class . ':ModificarUno')->add(\AutentificadorJWT::class . ':verificarRolSocio');
  })->add(\AutentificadorJWT::class . ':verificarToken');

  $app->group('/informes', function(RouteCollectorProxy $group)
  {
    $group->get('/{id}', \InformesController::class . ':ObtenerInformes');
  });

  
  $app->group('/pedidos', function(RouteCollectorProxy $group)
  {
    $group->get('[/]', \PedidoController::class. ':TraerTodos');
    $group->get('/{pedido}', \PedidoController::class. ':TraerUno');
    $group->post('[/]', \PedidoController::class. ':CargarUno');
    $group->delete('/{id}', \PedidoController::class . ':BorrarUno')->add(\AutentificadorJWT::class . ':verificarRolSocio');
    $group->put('[/]', \PedidoController::class . ':ModificarUno');
    $group->post('/cerrar/{numero_de_pedido}', \PedidoController::class . ':CerrarPedidoController');
    $group->post('/foto', \PedidoController::class . ':TomarFoto')->add(\AutentificadorJWT::class . ':verificarRolMozo');
  })->add(\AutentificadorJWT::class . ':verificarToken');

  //Cliente
  $app->group('/cliente', function(RouteCollectorProxy $group)
  {
    $group->post('[/]', \ClienteController::class . ':CargarUno');
    $group->get('[/]', \ClienteController::class . ':TraerTodos');
    $group->get('/{id}', \ClienteController::class . ':TraerUno');
    $group->delete('/{id}', \ClienteController::class . ':BorrarUno')->add(\AutentificadorJWT::class . ':verificarRolSocio');
    $group->post('/asignar', \ClienteController::class . ':AsignarCliente');
    $group->post('/cargar', \ClienteController::class . ':CargarCSV');
    $group->post('/demora', \ClienteController::class . ':VerDemora');
    //$group->get('/descargar', \ClienteController::class . ':GuardarCSV');
  });
  
  

  //Encuesta
  $app->group('/encuesta', function(RouteCollectorProxy $group)
  {
    $group->post('[/]', \EncuestaController::class . ':CargarUno');
    $group->get('[/]', \EncuestaController::class . ':TraerTodos');
    $group->get('/{id}', \EncuestaController::class . ':TraerUno');
    $group->delete('/{id}', \EncuestaController::class . ':BorrarUno');
  })->add(\AutentificadorJWT::class . ':verificarToken');
  
  //Factura
  $app->group('/factura', function(RouteCollectorProxy $group)
  {
    $group->post('[/]', \FacturaController::class . ':CargarUno');
    $group->get('[/]', \FacturaController::class . ':TraerTodos')->add(\AutentificadorJWT::class . ':verificarRolSocio');
    $group->get('/{id}', \FacturaController::class . ':TraerUno')->add(\AutentificadorJWT::class . ':verificarRolSocio');
    $group->delete('/{id}', \FacturaController::class . ':BorrarUno')->add(\AutentificadorJWT::class . ':verificarRolSocio');
  })->add(\AutentificadorJWT::class . ':verificarToken');
  
  //RegistroDeAcciones
  $app->group('/registrodeacciones', function(RouteCollectorProxy $group)
  {
    $group->post('[/]', \RegistroAccionesControllers::class . ':CargarUno');
    $group->get('[/]', \RegistroAccionesControllers::class . ':TraerTodos');
    $group->get('/{id}', \RegistroAccionesControllers::class . ':TraerUno');
    $group->delete('/{id}', \RegistroAccionesControllers::class . ':BorrarUno');
  })->add(\AutentificadorJWT::class . ':verificarToken')->add(\AutentificadorJWT::class . ':verificarRolSocio');
  
  //RegistroDeIngresos
  $app->group('/registrodeingresos', function(RouteCollectorProxy $group)
  {
    $group->post('[/]', \RegistroIngresosController::class . ':CargarUno');
    $group->get('[/]', \RegistroIngresosController::class . ':TraerTodos');
    $group->get('/{id}', \RegistroIngresosController::class . ':TraerUno');
    $group->delete('/{id}', \RegistroIngresosController::class . ':BorrarUno');
    $group->get('/descargar', \RegistroIngresosController::class . ':DescargarPDF');
  })->add(\AutentificadorJWT::class . ':verificarToken')->add(\AutentificadorJWT::class . ':verificarRolSocio');
 

  //Lista
  $app->group('/lista', function(RouteCollectorProxy $group)
  {
    $group->post('[/]', \ListaController::class . ':CargarUno');
    $group->get('[/]', \ListaController::class . ':TraerTodos');
    $group->get('/{numero_de_pedido}', \ListaController::class . ':TraerUno');
    $group->delete('/{numero_de_pedido}', \ListaController::class . ':BorrarUno')->add(\AutentificadorJWT::class . ':verificarRolSocio');
    $group->post('/entregar/{id}', \ListaController::class . ':Entregar')->add(\AutentificadorJWT::class . ':verificarRolMozo');
    $group->post('/listo/{id}', \ListaController::class . ':Listo');
  })->add(\AutentificadorJWT::class . ':verificarToken');

  $app->run();
?>
