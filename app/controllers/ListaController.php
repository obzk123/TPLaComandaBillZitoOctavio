<?php

    require_once("E:/xampp/htdocs/programacion_3/Trabajo-practico-LaComanda/app/models/Lista.php");

    class ListaController extends Lista implements IApiUsable
    {
        public function CargarUno($request, $response, $args)
        {
            $body = $request->getParsedBody();
            $numero_de_pedido = $body['numero_de_pedido'];
            $idProducto = $body['idProducto'];

            if($numero_de_pedido != null && $idProducto != null)
            {
                $producto = Producto::ObtenerProducto($idProducto);
                $pedido = Pedido::ObtenerPedido($numero_de_pedido);
                if($producto == null || $pedido == null || $pedido->estado == 'finalizado')
                {
                    $response->getBody()->write("Producto o pedido no existe verificar IDs");
                    return $response->withHeader('Content-Type', 'application/json');
                }
                

                if($pedido->tiempoEstimado == null || $pedido->tiempoEstimado < $producto->tiempo)
                {
                    $pedido->tiempoEstimado = $producto->tiempo;
                    $pedido->ActualizarTiempo();
                }

                $lista = new Lista();
                $lista->numero_de_pedido = $numero_de_pedido;
                $lista->idProducto = $idProducto;
                $lista->estado = $producto->tipo;
            
                $lista->CrearLista();

                $registro = new RegistroDeAcciones();

                $peticionHeader = $request->getHeaderLine("Authorization");
                $token = trim(explode("Bearer", $peticionHeader)[1]);
                $tokenData = JsonWebToken::ObtenerData($token);
                $registro->idUsuario = $tokenData[0];
                $registro->accion = "Crear lista";
                $registro->CrearRegistroDeAcciones();

                $response->getBody()->write("Lista creada");
            }

            return $response->withHeader('Content-Type', 'application/json');
        }

        public function TraerUno($request, $response, $args)
        {
            $numero_de_pedido = $args['numero_de_pedido'];
            $lista = Lista::obtenerLista($numero_de_pedido);
            if($lista != null)
            {
              $payload = json_encode($lista);
              $response->getBody()->write($payload);
            }
            else
            {
              $response->getBody()->write("Lista no encontrada");
            }
            
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function TraerTodos($request, $response, $args)
        {
            $peticionHeader = $request->getHeaderLine("Authorization");
            $token = trim(explode("Bearer", $peticionHeader)[1]);
            $tokenData = JsonWebToken::ObtenerData($token);
            switch($tokenData[1])
            {
                case 'mozo':
                    $lista = Lista::obtenerListaPorRol('listo');
                    break;
                
                case 'bartender':
                    $lista = Lista::obtenerListaPorRol('bartender');
                    break;
                
                case 'cocinero':
                    $lista = Lista::obtenerListaPorRol('cocinero');
                    break;
                
                case 'cervecero':
                    $lista = Lista::obtenerListaPorRol('cervecero');
                    break;

                default:
                    $lista = Lista::obtenerTodas();
                    break;
            }

            $payload = json_encode(array("listaController" => $lista));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function ModificarUno($request, $response, $args)
        {

        }

        public function BorrarUno($request, $response, $args)
        {
            $numero_de_pedido = $args['numero_de_pedido'];
            if(Lista::borrarLista($numero_de_pedido))
            {
                $payload = json_encode(array("mensaje" => "Lista borrada con exito"));
            }
            else
            {
                $payload = json_encode(array("mensaje" => "Lista no encontrada"));
            }   

            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function Entregar($request, $response, $args)
        {
            $id = $args['id'];
            $lista = Lista::obtenerListaPorID($id);
            if($lista != null && $lista->estado == 'listo')
            {
                $pedido = Pedido::ObtenerPedido($lista->numero_de_pedido);
                $producto = Producto::ObtenerProducto($lista->idProducto);
                $pedido->precioTotal += $producto->precio;
                $pedido->ActualizarPrecio();

                $mesa = Mesa::ObtenerMesa($pedido->mesaID);
                $mesa->estado = "cliente comiendo";
                $mesa->ActualizarEstado();

                Lista::CambiarEstado($id, "entregado");
                $response->getBody()->write("Entregado");
                
            }else
            {
                $response->getBody()->write("No se pudo entregar");
            }
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function Listo($request, $response, $args)
        {
            $id = $args['id'];
            $lista = Lista::obtenerListaPorID($id);
            if($lista != null && $lista->estado != 'listo' && $lista->estado != 'entregado')
            {
                $peticionHeader = $request->getHeaderLine("Authorization");
                $token = trim(explode("Bearer", $peticionHeader)[1]);
                $tokenData = JsonWebToken::ObtenerData($token);
                $producto = Producto::ObtenerProducto($lista->idProducto);

                if($producto->tipo == $tokenData[1])
                {
                    Lista::CambiarEstado($id, 'listo');
                    $response->getBody()->write("Listo");
                }
                else
                {
                    $response->getBody()->write("Esta lista no corresponde al tipo de usuario");
                }    
            }
            else
            {
                $response->getBody()->write("No se pudo terminar");
            }

            return $response->withHeader('Content-Type', 'application/json');
        }
    }
?>