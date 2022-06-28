<?php

    

    class Informes
    {
        public static function MejorComentario()
        {
            $listaEncuestas = Encuesta::obtenerTodas();
            if($listaEncuestas != null)
            {
                $mejorEncuesta = null;
                foreach($listaEncuestas as $encuesta)
                {
                    if($mejorEncuesta == null || $encuesta->ObtenerPromedio() > $mejorEncuesta->ObtenerPromedio())
                    {
                        $mejorEncuesta = $encuesta;
                    }
                }
                return $mejorEncuesta;
            }

            return null;
        }

        public static function EncuestasOrdenadas()
        {
            $listaEncuestas = Encuesta::obtenerTodas();
            if($listaEncuestas != null)
            {
                $encuestaAux = null;
                for($i = 0; $i < count($listaEncuestas) - 1; $i++)
                {
                    for($j = $i + 1; $j < count($listaEncuestas); $j++)
                    {
                        if($listaEncuestas[$i]!= null && $listaEncuestas[$j] != null && $listaEncuestas[$i]->ObtenerPromedio() < $listaEncuestas[$j]->ObtenerPromedio())
                        {
                            $encuestaAux = $listaEncuestas[$i];
                            $listaEncuestas[$i] = $listaEncuestas[$j];
                            $listaEncuestas[$j] = $encuestaAux;
                        }
                    }
                }
            }
            return $listaEncuestas;
        }

        public static function MesaMasUsada()
        {
            $listaPedidos = Pedido::ObtenerPedidos();
            if($listaPedidos != null)
            {
                $arrayMesasID = array();
                foreach($listaPedidos as $pedido)
                {
                    array_push($arrayMesasID, $pedido->mesaID);
                }

                $arrayMesasSumadas = array_count_values($arrayMesasID);

                $mesaMayor = null;
                foreach($arrayMesasSumadas as $key=>$valor)
                {
                    if($mesaMayor == null || $valor > $mesaMayor)
                    {
                        $mesaMayor = $key;
                    }
                }
                return Mesa::ObtenerMesa($mesaMayor);
            }
        }
    }
?>