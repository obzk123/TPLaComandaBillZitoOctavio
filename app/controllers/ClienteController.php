<?php

    require_once("E:/xampp/htdocs/programacion_3/Trabajo-practico-LaComanda/app/models/Cliente.php");

    class ClienteController extends Cliente implements IApiUsable
    {
        public function CargarUno($request, $response, $args)
        {
            $body = $request->getParsedBody();
            $nombre = $body['nombre'];
            if($nombre != null)
            {
                $cliente = new Cliente();
                $cliente->nombre = $nombre;
                $cliente->CrearCliente();

                $response->getBody()->write("Cliente creado");

                $registro = new RegistroDeAcciones();

                $peticionHeader = $request->getHeaderLine("Authorization");
                $token = trim(explode("Bearer", $peticionHeader)[1]);
                $tokenData = JsonWebToken::ObtenerData($token);
        
                $registro->idUsuario = $tokenData[0];
                $registro->accion = "Creacion de cliente";
                $registro->CrearRegistroDeAcciones();
            }

            return $response->withHeader('Content-Type', 'application/json');
        }

        public function TraerUno($request, $response, $args)
        {
            $id = $args['id'];
            $cliente = Cliente::obtenerCliente($id);
            if($cliente != null)
            {
              $payload = json_encode($cliente);
              $response->getBody()->write($payload);

              $registro = new RegistroDeAcciones();

              $peticionHeader = $request->getHeaderLine("Authorization");
              $token = trim(explode("Bearer", $peticionHeader)[1]);
              $tokenData = JsonWebToken::ObtenerData($token);
      
              $registro->idUsuario = $tokenData[0];
              $registro->accion = "Traer cliente";
              $registro->CrearRegistroDeAcciones();
            }
            else
            {
              $response->getBody()->write("Cliente no encontrado");
            }
            
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function TraerTodos($request, $response, $args)
        {
            $lista = Cliente::obtenerTodos();
            $payload = json_encode(array("listaClientes" => $lista));
            $response->getBody()->write($payload);
            $registro = new RegistroDeAcciones();

            $peticionHeader = $request->getHeaderLine("Authorization");
            $token = trim(explode("Bearer", $peticionHeader)[1]);
            $tokenData = JsonWebToken::ObtenerData($token);
    
            $registro->idUsuario = $tokenData[0];
            $registro->accion = "Traer todos los clientes";
            $registro->CrearRegistroDeAcciones();
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function ModificarUno($request, $response, $args)
        {

        }

        public function BorrarUno($request, $response, $args)
        {
            $id = $args['id'];
            if(Cliente::borrarCliente($id))
            {
                $payload = json_encode(array("mensaje" => "Cliente borrado con exito"));
                $registro = new RegistroDeAcciones();

                $peticionHeader = $request->getHeaderLine("Authorization");
                $token = trim(explode("Bearer", $peticionHeader)[1]);
                $tokenData = JsonWebToken::ObtenerData($token);
        
                $registro->idUsuario = $tokenData[0];
                $registro->accion = "Borrar cliente";
                $registro->CrearRegistroDeAcciones();
            }
            else
            {
                $payload = json_encode(array("mensaje" => "Cliente no encontrado"));
            }   

            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function AsignarCliente($request, $response, $args)
        {
            $body = $request->getParsedBody();
            $clienteID = $body['clienteID'];
            $mesaID = $body['mesaID'];

            $cliente = Cliente::obtenerCliente($clienteID);
            $mesa = Mesa::ObtenerMesa($mesaID);
            if($cliente == null && $mesa != null)
            {
                $response->getBody()->write("Cliente o mesa no encontrado");
                return $response->withHeader('Content-Type', 'application/json');
            }

            if($mesa->estado != 'cerrada')
            {
                $response->getBody()->write("Mesa ocupada");
                return $response->withHeader('Content-Type', 'application/json');
            }

            $mesa->estado = 'cliente esperando';
            $mesa->ActualizarEstado();

            $response->getBody()->write("Cliente asignado");
            return $response->withHeader('Content-Type', 'application/json');
        }

    }

?>