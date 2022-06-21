<?php


    class RegistroDeAcciones
    {
        public $idUsuario;
        public $accion;

        public function CrearRegistroDeAcciones()
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta('INSERT INTO registrodeacciones (idUsuario, accion) VALUES (:idUsuario, :accion)');
            $consulta->bindValue(':idUsuario', $this->idUsuario, PDO::PARAM_INT);
            $consulta->bindValue(':accion', $this->accion);
            $consulta->execute();
            return $accesoDatos->obtenerUltimoId();
        }

        public static function obtenerTodos()
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM registrodeacciones");
            $consulta->execute(); 
            return $consulta->fetchAll(PDO::FETCH_CLASS, 'RegistroDeAcciones');
        }

        public static function obtenerRegistroDeAcciones($id)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM registrodeacciones WHERE idUsuario = :idUsuario");
            $consulta->bindValue(':idUsuario', $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->fetchObject('RegistroDeAcciones');
        }

        public static function modificarRegistroDeAcciones($id)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta();
            $consulta->bindValue();
            $consulta->execute();
            return $consulta->rowCount();
        }

        public static function borrarRegistroDeAcciones($id)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("DELETE FROM registrodeacciones WHERE idUsuario = :id");
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->rowCount();
        }
    }

?>