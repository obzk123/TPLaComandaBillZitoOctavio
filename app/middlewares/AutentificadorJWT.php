<?php

    require_once("E:/xampp/htdocs/programacion_3/tp/app/models/JsonWebToken.php");
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
                    $data = JsonWebToken::ObtenerData($token);

                    $response = $handler->handle($request);
                    $response->getBody()->write(json_encode($data));
                }
            }
            catch(Exception $e)
            {
                $response->getBody()->write(json_encode("Error"));
                $response = $response->withStatus(401);
            }

            return $response->withHeader('Content-Type', 'application/json');
        }
    }
