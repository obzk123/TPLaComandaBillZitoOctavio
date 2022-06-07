<?php

    require_once("E:/xampp/htdocs/programacion_3/Trabajo-practico-LaComanda/app/db/AccesoDatos.php");

    class Mesa
    {
        private $id;
        private $pedido;

        public function CrearMesa()
        {
            $acessoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $acessoDatos->prepararConsulta("INSERT INTO mesas (pedido) values (:pedido)");
            $consulta->bindValue(':pedido', $this->pedido, PDO::PARAM_STR);
            $consulta->execute();
            return $acessoDatos->obtenerUltimoId();
        }

        public static function ObtenerMesa($id)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM mesas WHERE id = :id");
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->fetchObject('Mesa');
        }

        public static function ObtenerMesas()
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM mesas");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS, "Mesa");
        }

        public function ModificarMesa()
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("UPDATE mesas SET pedido = :pedido WHERE id = :id");
            $consulta->bindValues(':id', $this->id, PDO::PARAM_INT);
            $consulta->bindValue(':pedido', $this->pedido, PDO::PARAM_STR);
            $consulta->execute();
            return $consulta->fetchObject('Mesa');
        }

        public static function EliminarMesa($id)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("DELETE FROM mesas WHERE id = :id");
            $consulta->bindValues(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta;
        }
    }

?>