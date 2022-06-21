<?php

    require_once("E:/xampp/htdocs/programacion_3/Trabajo-practico-LaComanda/app/models/RegistroDeAcciones.php");

    class RegistroAccionesControllers extends RegistroDeAcciones implements IApiUsable
    {
        public function CargarUno($request, $response, $args)
        {
            $body = $request->getParsedBody();
            $idUsuario = $body['idUsuario'];
            $accion = $body['accion'];

            if($idUsuario != null && $accion != null)
            {
                $registro = new RegistroDeAcciones();
                $registro->idUsuario = $idUsuario;
                $registro->accion = $accion;
                $registro->CrearRegistroDeAcciones();
                
                $response->getBody()->write("Registro de acciones creado");
                $response->getBody()->write(json_encode($registro));
            }
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function TraerUno($request, $response, $args)
        {
            $id = $args['id'];
            $registro = RegistroDeAcciones::obtenerRegistroDeAcciones($id);
            if($registro != null)
            {
              $payload = json_encode($registro);
              $response->getBody()->write($payload);
            }
            else
            {
              $response->getBody()->write("Registro de acciones no encontrado");
            }
            
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function TraerTodos($request, $response, $args)
        {
            $lista = RegistroDeAcciones::obtenerTodos();
            $payload = json_encode(array("listaRegistroDeAcciones" => $lista));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function ModificarUno($request, $response, $args)
        {

        }

        public function BorrarUno($request, $response, $args)
        {
            $id = $args['id'];
            if(RegistroDeAcciones::borrarRegistroDeAcciones($id))
            {
                $payload = json_encode(array("mensaje" => "Registro de acciones borrado con exito"));
            }
            else
            {
                $payload = json_encode(array("mensaje" => "Registro de acciones no encontrado"));
            }   

            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

?>