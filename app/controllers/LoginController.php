<?php


    class LoginController
    {
        public static function iniciarSesion($request, $response, $args)
        {
            $body = $request->getParsedBody();
            
            $usuario = $body['usuario'];
            $clave = $body['clave'];
            if($usuario == null || $clave == null)
            {
                $response->getBody()->write("Parametros incorrectos");
                $response = $response->withStatus(401);
                return $response;
            }
            
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM usuarios WHERE usuario = :usuario AND clave = :clave");
            $consulta->bindValue(':usuario', $usuario,PDO::PARAM_STR);
            $consulta->bindValue(':clave', $clave,PDO::PARAM_STR);
            $consulta->execute();
            $usuario = $consulta->fetchObject('Usuario');
            
            if($usuario != null && $usuario->fechaBaja == null && $usuario->suspencion == 0)
            {
                $data = array();
                array_push($data, $usuario->id);
                array_push($data, $usuario->rol);

                $registro = new RegistroDeIngresos();
                $registro->idUsuario  = $usuario->id;
                $registro->CrearRegistroDeIngresos();

                $token = JsonWebToken::CrearToken($data);
                $response->getBody()->write("Logeado");
                $response->getBody()->write(json_encode($token));
                
                return $response;
            }

            $response->getBody()->write("No se pudo logear");
            $response = $response->withStatus(401);
            return $response;
        }
    }

?>