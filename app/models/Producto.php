<?php

    require_once("E:/xampp/htdocs/programacion_3/tp/app/db/AccesoDatos.php");
    
    class Producto
    {
        public $id;
        public $nombre;
        public $tiempo;
        public $tipo;

        public function CrearProducto()
        {
            $acessoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $acessoDatos->prepararConsulta("INSERT INTO productos (nombre, tiempo, tipo) values (:nombre, :tiempo, :tipo)");
            $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':tiempo', $this->tiempo, PDO::PARAM_STR);
            $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
            $consulta->execute();
            return $acessoDatos->obtenerUltimoId();
        }

        public static function ObtenerProducto($id)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM productos WHERE id = :id");
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->fetchObject('Producto');
        }

        public static function ObtenerProductos()
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM productos");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS, "Producto");
        }

        public function ModificarProducto()
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("UPDATE productos SET nombre = :nombre, tiempo = :tiempo, tipo = :tipo WHERE id = :id");
            $consulta->bindValues(':id', $this->id, PDO::PARAM_INT);
            $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':tiempo', $this->tiempo, PDO::PARAM_STR);
            $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
            $consulta->execute();
            return $consulta->fetchObject('Producto');
        }

        public static function EliminarProducto($id)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("DELETE FROM productos WHERE id = :id");
            $consulta->bindValues(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta;
        }
    
    }


?>