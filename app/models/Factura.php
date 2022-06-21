<?php

    class Factura
    {
        public $id;
        public $numero_de_pedido;
        public $encuestaID;
        public $fechaSalida;

        public function CrearFactura()
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta('INSERT INTO facturas (numero_de_pedido, encuestaID, fechaSalida) VALUES (:numero_de_pedido, :encuestaID, :fechaSalida)');
            $consulta->bindValue(':numero_de_pedido', $this->numero_de_pedido, PDO::PARAM_INT);
            $consulta->bindValue(':encuestaID', $this->encuestaID, PDO::PARAM_INT);
            $consulta->bindValue(':fechaSalida', $this->fechaSalida);
            $consulta->execute();
            return $accesoDatos->obtenerUltimoId();
        }

        public static function obtenerTodas()
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM facturas");
            $consulta->execute(); 
            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Factura');
        }

        public static function obtenerFactura($id)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM facturas WHERE id = :id");
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->fetchObject('Factura');
        }

        public static function modificarFactura($id)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta();
            $consulta->bindValue();
            $consulta->execute(); 
            return $consulta->rowCount();
        }

        public static function borrarFactura($id)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("DELETE FROM facturas WHERE id = :id");
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->rowCount();
        }
    }

?>