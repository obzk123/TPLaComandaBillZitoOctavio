<?php

require_once "E:/xampp/htdocs/programacion_3/tp/app/models/Mesa.php";
require_once "E:/xampp/htdocs/programacion_3/tp/app/interfaces/IApiUsable.php";

    class MesaController extends Mesa implements IApiUsable
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