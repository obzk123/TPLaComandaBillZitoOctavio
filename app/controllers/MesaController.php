<?php

require_once "E:/xampp/htdocs/programacion_3/Trabajo-practico-LaComanda/app/models/Mesa.php";
require_once "E:/xampp/htdocs/programacion_3/Trabajo-practico-LaComanda/app/interfaces/IApiUsable.php";

    class MesaController extends Mesa implements IApiUsable
    {
        public function TraerUno($request, $response, $args)
        {
            $id = $args['id'];
            $mesa = Mesa::ObtenerMesa($id);

            if($mesa != null)
            {
                $response->getBody()->write(json_encode($mesa));
            }
            else
            {
                $response->getBody()->write("No se encontro la mesa");
            }
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function TraerTodos($request, $response, $args)
        {
            $listaMesas = Mesa::ObtenerMesas();
            $response->getBody()->write(json_encode($listaMesas));
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function CargarUno($request, $response, $args)
        {
            $body = $request->getParsedBody();
            $numero_de_mesa = $body['numero_de_mesa'];
            $estado = $body['estado'];
            if($numero_de_mesa != null && $estado != null)
            {
                $mesa = new Mesa();
                $mesa->numero_de_mesa = $numero_de_mesa;
                $mesa->estado = $estado;
                $mesa->CrearMesa();

                $response->getBody()->write("Mesa creada");
                $response->getBody()->write(json_encode($mesa));
            }
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function BorrarUno($request, $response, $args)
        {
            $numero_de_mesa = $args['numero_de_mesa'];

            if(Mesa::EliminarMesa($numero_de_mesa))
            {
                $payload = json_encode(array("mensaje" => "Mesa borrado con exito"));
            }
            else
            {
                $payload = json_encode(array("mensaje" => "Mesa no encontrada"));
            }   
        
            $response->getBody()->write($payload);

            return $response->withHeader('Content-Type', 'application/json');
        }
        
        public function ModificarUno($request, $response, $args)
        {
            $body = $request->getBody();
            $parametros = json_decode($body, true);
      
            $id = $parametros['id'];
            $numero_de_mesa = $parametros['numero_de_mesa'];
            $estado = $parametros['estado'];

            $mesa = Mesa::ObtenerMesa($id);
            if($mesa == null || $estado == null)
            {
                $response->getBody()->write("Mesa no encontrada o parametros incorrectos");
                return $response->withHeader('Content-Type', 'application/json');
            }

            $mesa->numero_de_mesa = $numero_de_mesa;
            $mesa->estado = $estado;

            if(Mesa::ModificarMesa($mesa))
            {
                $payload = json_encode(array("mensaje" => "Mesa modificada con exito"));
            }
            else
            {
                $payload = json_encode(array("mensaje" => "La mesa no se pudo modificar"));
            }

            $response->getBody()->write($payload);

            return $response->withHeader('Content-Type', 'application/json');
        }
    }
    
?>