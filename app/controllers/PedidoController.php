<?php

    require_once "E:/xampp/htdocs/programacion_3/Trabajo-practico-LaComanda/app/models/Pedido.php";
    require_once "E:/xampp/htdocs/programacion_3/Trabajo-practico-LaComanda/app/interfaces/IApiUsable.php";
    use Slim\Http\UploadedFile;

    class PedidoController extends Pedido implements IApiUsable
    {
        public function TraerUno($request, $response, $args)
        {
            $id = $args['id'];
            $pedido = Pedido::ObtenerPedido($id);

            if($pedido != null)
            {
                $response->getBody()->write(json_encode($pedido));
            }
            else
            {
                $response->getBody()->write("No se encontro el pedido");
            }
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function TraerTodos($request, $response, $args)
        {
            $listaPedidos = Pedido::ObtenerPedidos();
            $response->getBody()->write(json_encode($listaPedidos));
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function CargarUno($request, $response, $args)
        {
            $body = $request->getParsedBody();
            $mesaID = $body['mesaID'];
            $clienteID = $body['clienteID'];
            
            if($mesaID != null && $clienteID != null)
            {
                $mesa = Mesa::ObtenerMesa($mesaID);
                if($mesa == null || $mesa->estado == 'cerrada' || $mesa->estado == 'cliente pagando' || $mesa->estado == 'cliente comiendo')
                {
                    $response->getBody()->write("Mesa inexistente o ocupada");
                    return $response->withHeader('Content-Type', 'application/json');
                }
                
                $cliente = Cliente::obtenerCliente($clienteID);
                if($cliente == null)
                {
                    $response->getBody()->write("Cliente inexistente");
                    return $response->withHeader('Content-Type', 'application/json');
                }

                $peticionHeader = $request->getHeaderLine("Authorization");
                $token = trim(explode("Bearer", $peticionHeader)[1]);
                $tokenData = JsonWebToken::ObtenerData($token);

                $pedido = new Pedido();
                $pedido->fechaEntrada = date("Y-m-d-H:i:s");
                $pedido->mesaID = $mesaID;
                $pedido->clienteID = $clienteID;
                $pedido->usuarioID = $tokenData[0];
                $pedido->estado = "en preparacion";
                $pedido->tiempoDeEntrega = 0;
                $pedido->fueCancelado = 0;

                $pedido->CrearPedido();

                $response->getBody()->write("Pedido creado");
            }

            return $response->withHeader('Content-Type', 'application/json');
        }

        public function BorrarUno($request, $response, $args)
        {
            $id = $args['id'];
            if(Pedido::EliminarPedido($id))
            {
                $payload = json_encode(array("mensaje" => "Pedido borrado con exito"));
            }
            else
            {
                $payload = json_encode(array("mensaje" => "Pedido no encontrado"));
            }   
        
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        
        public function ModificarUno($request, $response, $args)
        {
            $body = $request->getBody();
            $parametros = json_decode($body, true);
      
            $id = $parametros['id'];
            $producto = $parametros['producto'];
            $mesa = $parametros['mesa'];
            $estado = $parametros['estado'];

            $pedido = Pedido::ObtenerPedido($id);
            if($pedido == null || $producto == null || $mesa == null || $estado == null)
            {
                $response->getBody()->write("Pedido no encontrado o parametros incorrectos");
                return $response->withHeader('Content-Type', 'application/json');
            }

            $pedido->producto = $producto;
            $pedido->mesa = $mesa;
            $pedido->estado = $estado;

            if(Pedido::ModificarPedido($pedido))
            {
                $payload = json_encode(array("mensaje" => "Pedido modificado con exito"));
            }
            else
            {
                $payload = json_encode(array("mensaje" => "El pedido no se pudo modificar"));
            }

            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function CerrarPedidoController($request, $response, $args)
        {
            
            $numero_de_pedido = $args['numero_de_pedido'];
            if($numero_de_pedido != null)
            {
                $pedido = Pedido::ObtenerPedido($numero_de_pedido);
                if($pedido != null && $pedido->estado != 'listo' && $pedido->fueCancelado == 0)
                {
                    $lista = Lista::ObtenerListaDeUnPedido($numero_de_pedido);
                    if($lista != null)
                    {
                        $response->getBody()->write("No se puede cerrar el pedido quedan productos pendientes");
                        return $response->withHeader('Content-Type', 'application/json');
                    }
                    
                    $pedido->CerrarPedido();
                    $mesa = Mesa::ObtenerMesa($pedido->mesaID);
                    $mesa->estado = 'cliente pagando';
                    $mesa->ActualizarEstado();
                    $response->getBody()->write("Pedido cerrado");
                    
                }else
                {
                    $response->getBody()->write("Pedido no encontrado");
                }
            }

            return $response->withHeader('Content-Type', 'application/json');
        }

        public function TomarFoto($request, $response, $args)
        {
            $body = $request->getParsedBody();
            $numero_de_pedido = $body['numero_de_pedido'];
            if($numero_de_pedido != null)
            {
                $pedido = Pedido::ObtenerPedido($numero_de_pedido);
                if($pedido != null && $pedido->fueCancelado == 0 && $pedido->estado != 'listo')
                {
                    $archivos = $request->getUploadedFiles();
                    $foto = $archivos['foto'];
                    $directorio = './images/';
                    $foto->moveTo($directorio . $numero_de_pedido . '.jpg');
                    $response->getBody()->write(json_encode('Foto cargada'));
                    return $response->withHeader('Content-Type', 'application/json');
                }
            }
            $response->getBody()->write(json_encode('No se encontro el pedido'));
            return $response->withHeader('Content-Type', 'application/json');

            
        }
    }

?>