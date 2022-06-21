<?php

    require_once "E:/xampp/htdocs/programacion_3/Trabajo-practico-LaComanda/app/models/Pedido.php";
    require_once "E:/xampp/htdocs/programacion_3/Trabajo-practico-LaComanda/app/interfaces/IApiUsable.php";

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
                if($mesa == null || $mesa->estado != 'cliente esperando')
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
                $pedido->fechaEntrada = date("Y-m-d");
                $pedido->mesaID = $mesaID;
                $pedido->clienteID = $clienteID;
                $pedido->usuarioID = $tokenData[0];
                $pedido->estado = "en preparacion";

                $pedido->CrearPedido();

                $response->getBody()->write("Pedido creado");
                $response->getBody()->write(json_encode($pedido));
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
    }

?>