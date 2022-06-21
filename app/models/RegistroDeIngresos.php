<?php

    class RegistroDeIngresos
    {
        public $idUsuario;
        public $horaIngreso;

        public function CrearRegistroDeIngresos()
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta('INSERT INTO registrodeingresos (idUsuario, horaIngreso) VALUES (:idUsuario, :horaIngreso)');
            $consulta->bindValue(':idUsuario', $this->idUsuario, PDO::PARAM_INT);
            $consulta->bindValue(':horaIngreso', time());
            $consulta->execute();
            return $accesoDatos->obtenerUltimoId();
        }

        public static function obtenerTodos()
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM registrodeingresos");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS, 'RegistroDeIngresos');
        }

        public static function obtenerRegistroDeIngresos($id)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM registrodeingresos WHERE idUsuario = :idUsuario");
            $consulta->bindValue(':idUsuario', $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->fetchObject('RegistroDeIngresos');
        }

        public static function modificarRegistroDeIngresos($id)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta();
            $consulta->bindValue();
            $consulta->execute(); 
            return $consulta->rowCount();
        }

        public static function borrarRegistroDeIngresos($id)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("DELETE FROM registrodeingresos WHERE idUsuario = :id");
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->rowCount();
        }
    }

?>