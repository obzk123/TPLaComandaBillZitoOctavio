<?php

    require_once("./models/JsonWebToken.php");

use Psr7Middlewares\Middleware\Expires;
use Slim\Psr7\Response;

    class AutentificadorJWT
    {

        public static function verificarToken($request, $handler)
        {
            $peticionHeader = $request->getHeaderLine("Authorization");
            $response = new Response();
            try
            {
                if($peticionHeader != null)
                {
                    $token = trim(explode("Bearer", $peticionHeader)[1]);
                    JsonWebToken::VerificarToken($token); 
                    $response = $handler->handle($request);
                }
                else
                {
                    $response->getBody()->write(json_encode("No esta logueado"));
                    $response = $response->withStatus(401);
                }
            }
            catch(Exception $e)
            {
                $response->getBody()->write(json_encode("Error token invalido"));
                $response = $response->withStatus(401);
            }

            return $response->withHeader('Content-Type', 'application/json');
        }

        public static function verificarRolMozo($request, $handler)
        {
            $peticionHeader = $request->getHeaderLine("Authorization");
            $response = new Response();
            try
            {
                $token = trim(explode("Bearer", $peticionHeader)[1]);
                $data = JsonWebToken::ObtenerData($token);
                if($data == 'socio' || $data == 'mozo')
                {
                    $response = $handler->handle($request);
                    $response->getBody()->write(json_encode($data));
                }
                else
                {
                    $response->getBody()->write(json_encode("No autorizado"));
                    $response = $response->withStatus(401);
                }

            }
            catch(Exception $e)
            {
                $response->getBody()->write(json_encode("Error token invalido"));
                $response = $response->withStatus(401);
            }
            return $response->withHeader('Content-Type', 'application/json');
        }

        public static function verificarRolBartender($request, $handler)
        {
            $peticionHeader = $request->getHeaderLine("Authorization");
            $response = new Response();
            try
            {
                $token = trim(explode("Bearer", $peticionHeader)[1]);
                $data = JsonWebToken::ObtenerData($token);
                if($data == 'socio' || $data == 'bartender')
                {
                    $response = $handler->handle($request);
                    $response->getBody()->write(json_encode($data));
                }
                else
                {
                    $response->getBody()->write(json_encode("No autorizado"));
                    $response = $response->withStatus(401);
                }

            }
            catch(Exception $e)
            {
                $response->getBody()->write(json_encode("Error token invalido"));
                $response = $response->withStatus(401);
            }
            return $response->withHeader('Content-Type', 'application/json');
        }

        public static function verificarRolCervecero($request, $handler)
        {
            $peticionHeader = $request->getHeaderLine("Authorization");
            $response = new Response();
            try
            {
                $token = trim(explode("Bearer", $peticionHeader)[1]);
                $data = JsonWebToken::ObtenerData($token);
                if($data == 'socio' || $data == 'cervecero')
                {
                    $response = $handler->handle($request);
                    $response->getBody()->write(json_encode($data));
                }
                else
                {
                    $response->getBody()->write(json_encode("No autorizado"));
                    $response = $response->withStatus(401);
                }

            }
            catch(Exception $e)
            {
                $response->getBody()->write(json_encode("Error token invalido"));
                $response = $response->withStatus(401);
            }
            return $response->withHeader('Content-Type', 'application/json');
        }

        public static function verificarRolCocinero($request, $handler)
        {
            $peticionHeader = $request->getHeaderLine("Authorization");
            $response = new Response();
            try
            {
                $token = trim(explode("Bearer", $peticionHeader)[1]);
                $data = JsonWebToken::ObtenerData($token);
                if($data == 'socio' || $data == 'cocinero')
                {
                    $response = $handler->handle($request);
                    $response->getBody()->write(json_encode($data));
                }
                else
                {
                    $response->getBody()->write(json_encode("No autorizado"));
                    $response = $response->withStatus(401);
                }

            }
            catch(Exception $e)
            {
                $response->getBody()->write(json_encode("Error token invalido"));
                $response = $response->withStatus(401);
            }
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
