<?php

    class RegistroIngresosController extends RegistroDeIngresos implements IApiUsable
    {
        public function CargarUno($request, $response, $args)
        {
            $body = $request->getParsedBody();
            $idUsuario = $body['idUsuario'];
            $horaIngreso = $body['horaIngreso'];

            if($idUsuario != null && $horaIngreso != null)
            {
                $registro = new RegistroDeIngresos();
                $registro->idUsuario = $idUsuario;
                $registro->horaIngreso = $horaIngreso;

                $registro->CrearRegistroDeIngresos();
                
                $response->getBody()->write("Registro de ingresos creado");
                $response->getBody()->write(json_encode($registro));
            }

            return $response->withHeader('Content-Type', 'application/json');
        }

        public function TraerUno($request, $response, $args)
        {
            $id = $args['id'];
            $registro = RegistroDeIngresos::obtenerRegistroDeIngresos($id);
            if($registro != null)
            {
              $payload = json_encode($registro);
              $response->getBody()->write($payload);
            }
            else
            {
              $response->getBody()->write("Registro de ingresos no encontrado");
            }
            
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function TraerTodos($request, $response, $args)
        {
            $lista = RegistroDeIngresos::obtenerTodos();
            $payload = json_encode(array("listaRegistro" => $lista));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function ModificarUno($request, $response, $args)
        {

        }

        public function BorrarUno($request, $response, $args)
        {
            $id = $args['id'];
            if(RegistroDeIngresos::borrarRegistroDeIngresos($id))
            {
                $payload = json_encode(array("mensaje" => "Registro de ingresos borrado con exito"));
            }
            else
            {
                $payload = json_encode(array("mensaje" => "Registro de ingresos no encontrado"));
            }   

            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function DescargarPDF($request, $response, $args)
        {
            $listaRegistros = RegistroDeIngresos::obtenerTodos();
            if($listaRegistros == null)
            {
                $response->getBody()->write("No se puede descargar");
                return $response->withHeader('Content-Type', 'application/json');
            }

            $string = 'id Usuario, Dia Ingreso, Hora Ingreso' . PHP_EOL;
            foreach($listaRegistros as $registro)
            {
                $string .= $registro->toString() . PHP_EOL;
            }

            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial','B',16);
            $pdf->MultiCell(150,8,utf8_decode($string),'1','L',0);
            $pdf->Output('D', 'registroingresos.pdf');

            $response->getBody()->write($string);
            $response->getBody()->write("Descarga con exito");
            
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
?>