<?php

    require_once "E:/xampp/htdocs/programacion_3/Trabajo-practico-LaComanda/app/models/Producto.php";
    require_once "E:/xampp/htdocs/programacion_3/Trabajo-practico-LaComanda/app/interfaces/IApiUsable.php";

    class ProductoController extends Producto implements IApiUsable
    {
        public function TraerUno($request, $response, $args)
        {
            return $response;
        }

        public function TraerTodos($request, $response, $args)
        {
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