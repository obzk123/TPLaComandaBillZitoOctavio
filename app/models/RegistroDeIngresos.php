<?php

    class RegistroDeIngresos
    {
        public $idUsuario;
        public $diaIngreso;
        public $horaIngreso;

        public function CrearRegistroDeIngresos()
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta('INSERT INTO registrodeingresos (idUsuario, diaIngreso, horaIngreso) VALUES (:idUsuario, :diaIngreso , :horaIngreso)');
            $consulta->bindValue(':idUsuario', $this->idUsuario, PDO::PARAM_INT);
            $consulta->bindValue(':diaIngreso', date('Y-m-d'));
            $consulta->bindValue(':horaIngreso', date('H:i:s'));
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

        public function toString()
        {  
            return $this->idUsuario . ', ' . $this->diaIngreso . ', '. $this->horaIngreso;
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