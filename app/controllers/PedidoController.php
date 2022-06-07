<?php

    require_once "E:/xampp/htdocs/programacion_3/tp/app/models/Pedido.php";
    require_once "E:/xampp/htdocs/programacion_3/tp/app/interfaces/IApiUsable.php";

    class PedidoController extends Pedido implements IApiUsable
    {
        public function TraerUno($request, $response, $args)
        {
            $response->getBody()->write("hola");
            return $response;
        }

        public function TraerTodos($request, $response, $args)
        {
            $response->getBody()->write("hola");
            return $response;
        }

        public function CargarUno($request, $response, $args)
        {
            return $response;
        }

        public function BorrarUno($request, $response, $args)
        {
            return $response;
        }
        
        public function ModificarUno($request, $response, $args)
        {
            return $response;
        }
    }

?>