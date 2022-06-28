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

        public function CargarCSV($request, $response, $args)
        {
            $archivo = $request->getUploadedFiles()['archivoCSV'];
            $split = explode(".", $archivo->getClientFilename());
            $extension = end($split);

            if($extension == 'csv')
            {
                $stream = $archivo->getStream();
                $lineas = explode(PHP_EOL, $stream);
                array_splice($lineas, 0, 1);
                foreach($lineas as $linea)
                {
                    $explode = explode(',', $linea);
                    $nuevoCliente = new Cliente();
                    $nuevoCliente->id = $explode[0];
                    $nuevoCliente->nombre = $explode[1];
                    $nuevoCliente->CrearCliente();
                }
                $response->getBody()->write("Archivo correcto");
                return $response->withHeader('Content-Type', 'application/json');
            }

            $response->getBody()->write("Archivo incorrecto");
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function GuardarCSV($request, $response, $args)
        {
            $listaClientes = Cliente::obtenerTodos();
            if($listaClientes != null)
            {
                $string = 'id,nombre' . PHP_EOL;
                foreach($listaClientes as $cliente)
                {
                    $string .= $cliente->GetCSV() . PHP_EOL;
                }

                $file = "clientes.csv";
                $txt = fopen($file, "w");
                fwrite($txt, $string);
                fclose($txt);

                header('Content-Description: File Transfer');
                header('Content-Disposition: attachment; filename='.basename($file));
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                header("Content-Type: text/plain");
                readfile($file);
            }
            else
            {
                $response->getBody()->write(json_encode('Lista de clientes vacia'));
            }
            return $response->withHeader('Content-Type', 'application/json');
        }


        public function VerDemora($request, $response, $args)
        {
            $body = $request->getParsedBody();
            $mesaID = $body['mesaID'];
            $numero_de_pedido = $body['numero_de_pedido'];

            if($mesaID == null || $numero_de_pedido == null)
            {
                $response->getBody()->write("Datos incorrectos");
                return $response->withHeader('Content-Type', 'application/json');
            }

            $mesa = Mesa::ObtenerMesa($mesaID);
            $pedido = Pedido::ObtenerPedido($numero_de_pedido);
            if($mesa == null || $pedido == null)
            {
                $response->getBody()->write("Numero de mesa o pedido incorrecto");
                return $response->withHeader('Content-Type', 'application/json');
            }

            $response->getBody()->write(json_encode('Tiempo estimado: ' . $pedido->tiempoEstimado));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

?>