<?php

    require_once("E:/xampp/htdocs/programacion_3/Trabajo-practico-LaComanda/app/db/AccesoDatos.php");
    require_once("E:/xampp/htdocs/programacion_3/Trabajo-practico-LaComanda/app/models/JsonWebToken.php");

    class LoginController
    {
        public static function iniciarSesion($request, $response, $args)
        {
            $body = $request->getParsedBody();
            
            $usuario = $body['usuario'];
            $clave = $body['clave'];

            
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM usuarios WHERE usuario = :usuario AND clave = :clave");
            $consulta->bindValue(':usuario', $usuario,PDO::PARAM_STR);
            $consulta->bindValue(':clave', $clave,PDO::PARAM_STR);
            $consulta->execute();
            $usuario = $consulta->fetchObject('Usuario');
            
            if($usuario != null)
            {
                $token = JsonWebToken::CrearToken($usuario->rol);
                $response->getBody()->write("Logeado");
                $response->getBody()->write(json_encode($token));
                return $response;
            }

            $response->getBody()->write("No se pudo logear");
            
            return $response;
        }
    }

?>