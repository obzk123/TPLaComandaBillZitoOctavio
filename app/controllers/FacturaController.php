<?php

    require_once("E:/xampp/htdocs/programacion_3/Trabajo-practico-LaComanda/app/models/Factura.php");

    class FacturaController extends Factura implements IApiUsable
    {
        public function CargarUno($request, $response, $args)
        {
            $body = $request->getParsedBody();
            $numero_de_pedido = $body['numero_de_pedido'];
            $encuestaID = $body['encuestaID'];
            $fechaSalida = $body['fechaSalida'];

            if($numero_de_pedido != null && $encuestaID != null && $fechaSalida != null)
            {
                $factura = new Factura();
                $factura->numero_de_pedido = $numero_de_pedido;
                $factura->encuestaID = $encuestaID;
                $factura->fechaSalida = $fechaSalida;

                $factura->CrearFactura();

                $registro = new RegistroDeAcciones();

                $peticionHeader = $request->getHeaderLine("Authorization");
                $token = trim(explode("Bearer", $peticionHeader)[1]);
                $tokenData = JsonWebToken::ObtenerData($token);
        
                $registro->idUsuario = $tokenData[0];
                $registro->accion = "Crear factura";
                $registro->CrearRegistroDeAcciones();

                $response->getBody()->write("Factura creada");
                $response->getBody()->write(json_encode($factura));
            }
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function TraerUno($request, $response, $args)
        {
            $id = $args['id'];
            $factura = Factura::obtenerFactura($id);
            if($factura != null)
            {
              $payload = json_encode($factura);
              $response->getBody()->write($payload);
              $registro = new RegistroDeAcciones();

              $peticionHeader = $request->getHeaderLine("Authorization");
              $token = trim(explode("Bearer", $peticionHeader)[1]);
              $tokenData = JsonWebToken::ObtenerData($token);
      
              $registro->idUsuario = $tokenData[0];
              $registro->accion = "Traer factura";
              $registro->CrearRegistroDeAcciones();
            }
            else
            {
              $response->getBody()->write("Factura no encontrada");
            }
            
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function TraerTodos($request, $response, $args)
        {
            $lista = Factura::obtenerTodas();
            $payload = json_encode(array("listaFactura" => $lista));
            $response->getBody()->write($payload);
            $registro = new RegistroDeAcciones();

            $peticionHeader = $request->getHeaderLine("Authorization");
            $token = trim(explode("Bearer", $peticionHeader)[1]);
            $tokenData = JsonWebToken::ObtenerData($token);
    
            $registro->idUsuario = $tokenData[0];
            $registro->accion = "Traer todas las facturas";
            $registro->CrearRegistroDeAcciones();
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function ModificarUno($request, $response, $args)
        {

        }

        public function BorrarUno($request, $response, $args)
        {
            $id = $args['id'];
            if(Factura::borrarFactura($id))
            {
                $payload = json_encode(array("mensaje" => "Factura borrada con exito"));
                $registro = new RegistroDeAcciones();

                $peticionHeader = $request->getHeaderLine("Authorization");
                $token = trim(explode("Bearer", $peticionHeader)[1]);
                $tokenData = JsonWebToken::ObtenerData($token);
        
                $registro->idUsuario = $tokenData[0];
                $registro->accion = "Borrar factura";
                $registro->CrearRegistroDeAcciones();
            }
            else
            {
                $payload = json_encode(array("mensaje" => "Factura no encontrada"));
            }   

            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

?>