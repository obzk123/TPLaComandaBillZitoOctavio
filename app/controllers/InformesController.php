<?php

    require_once('E:/xampp/htdocs/programacion_3/Trabajo-practico-LaComanda/app/models/Informes.php');

    class InformesController
    {
        public function ObtenerInformes($request, $response, $args)
        {
            $id = $args['id'];

            if($id > 0 && $id < 10)
            {
                switch($id)
                {
                    case 1:
                        $listaInformes = Informes::EncuestasOrdenadas();
                        if($listaInformes != null)
                        {
                            foreach($listaInformes as $informe)
                            {
                                $string = 'id: ' . $informe->id . ' Promedio: ' . $informe->ObtenerPromedio() . PHP_EOL;
                                $response->getBody()->write($string);
                            }
                        } 
                    break;
                    case 2:
                        $mesa = Informes::MesaMasUsada();
                        if($mesa != null) $response->getBody()->write(json_encode($mesa));
                    break;
                }
            }
            else
            {
                $response->getBody()->write(json_encode('Error al obtener el informe'));
            }
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
?>