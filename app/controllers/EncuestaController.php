<?php

    require_once("E:/xampp/htdocs/programacion_3/Trabajo-practico-LaComanda/app/models/Encuesta.php");

    class EncuestaController extends Encuesta implements IApiUsable
    {
        public function CargarUno($request, $response, $args)
        {
            $body = $request->getParsedBody();
            $clienteID = $body['clienteID'];
            $numero_de_pedido = $body['numero_de_pedido'];
            $puntajeMesa = $body['puntajeMesa'];
            $puntajeRestorant = $body['puntajeRestorant'];
            $puntajeCocinero = $body['puntajeCocinero'];
            $puntajeMozo = $body['puntajeMozo'];

            if(Encuesta::VerificarEncuestaExistente($numero_de_pedido) != null)
            {
                $response->getBody()->write("El numero de pedido ya tiene una encuesta");
                return $response->withHeader('Content-Type', 'application/json');
            }

            $cliente = Cliente::obtenerCliente($clienteID);
            $pedido = Pedido::ObtenerPedido($numero_de_pedido);
            if($cliente != null && $pedido != null && $pedido->estado == 'finalizado' && $puntajeMesa <= 10 && $puntajeMesa >= 1 && $puntajeRestorant <= 10 && $puntajeRestorant >= 1 && $puntajeCocinero <= 10 && $puntajeCocinero >= 1 && $puntajeMozo <= 10 && $puntajeMozo >= 1)
            {
                $encuesta = new Encuesta();
                $encuesta->clienteID = $clienteID;
                $encuesta->numero_de_pedido = $numero_de_pedido;
                $encuesta->puntajeMesa = $puntajeMesa;
                $encuesta->puntajeRestorant = $puntajeRestorant;
                $encuesta->puntajeCocinero = $puntajeCocinero;
                $encuesta->puntajeMozo = $puntajeMozo;
                $encuesta->CrearEncuesta();
                $response->getBody()->write("Encuesta creada");

                $registro = new RegistroDeAcciones();

                $peticionHeader = $request->getHeaderLine("Authorization");
                $token = trim(explode("Bearer", $peticionHeader)[1]);
                $tokenData = JsonWebToken::ObtenerData($token);
        
                $registro->idUsuario = $tokenData[0];
                $registro->accion = "Encuesta creada";
                $registro->CrearRegistroDeAcciones();
            }
            else
            {
                $response->getBody()->write("Error al crear la encuesta");
            }
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function TraerUno($request, $response, $args)
        {
            $id = $args['id'];
            $encuesta = Encuesta::obtenerEncuesta($id);
            if($encuesta != null)
            {
              $payload = json_encode($encuesta);
              $response->getBody()->write($payload);
              $registro = new RegistroDeAcciones();

              $peticionHeader = $request->getHeaderLine("Authorization");
              $token = trim(explode("Bearer", $peticionHeader)[1]);
              $tokenData = JsonWebToken::ObtenerData($token);
      
              $registro->idUsuario = $tokenData[0];
              $registro->accion = "Traer encuesta";
              $registro->CrearRegistroDeAcciones();
            }
            else
            {
              $response->getBody()->write("Encuesta no encontrada");
            }

            return $response->withHeader('Content-Type', 'application/json');
        }

        public function TraerTodos($request, $response, $args)
        {
            $lista = Encuesta::obtenerTodas();
            $payload = json_encode(array("listaEncuesta" => $lista));
            $response->getBody()->write($payload);

            $registro = new RegistroDeAcciones();

            $peticionHeader = $request->getHeaderLine("Authorization");
            $token = trim(explode("Bearer", $peticionHeader)[1]);
            $tokenData = JsonWebToken::ObtenerData($token);
    
            $registro->idUsuario = $tokenData[0];
            $registro->accion = "Traer todas las encuestas";
            $registro->CrearRegistroDeAcciones();

            return $response->withHeader('Content-Type', 'application/json');
        }

        public function ModificarUno($request, $response, $args)
        {

        }

        public function BorrarUno($request, $response, $args)
        {
            $id = $args['id'];
            if(Encuesta::borrarEncuesta($id))
            {
                $payload = json_encode(array("mensaje" => "Encuesta borrada con exito"));
                $registro = new RegistroDeAcciones();

                $peticionHeader = $request->getHeaderLine("Authorization");
                $token = trim(explode("Bearer", $peticionHeader)[1]);
                $tokenData = JsonWebToken::ObtenerData($token);
        
                $registro->idUsuario = $tokenData[0];
                $registro->accion = "Borrar una encuesta";
                $registro->CrearRegistroDeAcciones();
            }
            else
            {
                $payload = json_encode(array("mensaje" => "Encuesta no encontrada"));
            }   
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

    }

?>