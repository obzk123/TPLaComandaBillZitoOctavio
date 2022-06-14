<?php

  require_once "E:/xampp/htdocs/programacion_3/Trabajo-practico-LaComanda/app/models/Usuario.php";
  require_once "E:/xampp/htdocs/programacion_3/Trabajo-practico-LaComanda/app/interfaces/IApiUsable.php";

class UsuarioController extends Usuario implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $usuario = $parametros['usuario'];
        $clave = $parametros['clave'];
        $rol = $parametros['rol'];

        if($usuario == null || $clave == null || $rol == null)
        {
          $response->getBody()->write("Error al recibir los parametros");
          return $response->withHeader('Content-Type', 'application/json');
        }

        // Creamos el usuario
        $usr = new Usuario();
        $usr->usuario = $usuario;
        $usr->clave = $clave;
        $usr->rol = $rol;
        $usr->crearUsuario();

        $payload = json_encode(array("mensaje" => "Usuario creado con exito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre
        $usr = $args['usuario'];
        $usuario = Usuario::obtenerUsuario($usr);
        if($usuario != null)
        {
          $payload = json_encode($usuario);
          $response->getBody()->write($payload);
        }
        else
        {
          $response->getBody()->write("Usuario no encontrado");
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Usuario::obtenerTodos();
        $payload = json_encode(array("listaUsuario" => $lista));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $body = $request->getBody();
        $parametros = json_decode($body, true);
      
        $username = $parametros['usuario'];
        $clave = $parametros['clave'];

        $usuario = Usuario::obtenerUsuario($username);
        if($usuario == null || $clave == null)
        {
          $response->getBody()->write("Usuario no encontrado o parametros incorrectos");
          return $response->withHeader('Content-Type', 'application/json');
        }

        $usuario->clave = $clave;
        if(Usuario::modificarUsuario($usuario))
        {
          $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
        }
        else
        {
          $payload = json_encode(array("mensaje" => "El usuario no se pudo modificar"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
      $usuarioNombre = $args['usuario'];
      
      if(Usuario::borrarUsuario($usuarioNombre))
      {
        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "Usuario no encontrado"));
      }   

      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
    }
}
