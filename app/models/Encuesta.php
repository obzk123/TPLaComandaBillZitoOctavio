<?php

    class Encuesta
    {
        public $id;
        public $puntajeMesa;
        public $puntajeRestorant;
        public $puntajeCocinero;
        public $puntajeMozo;

        public function CrearEncuesta()
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta('INSERT INTO encuestas (puntajeMesa, puntajeRestorant, puntajeCocinero, puntajeMozo) VALUES (:puntajeMesa, :puntajeRestorant, :puntajeCocinero, :puntajeMozo)');
            $consulta->bindValue(':puntajeMesa', $this->puntajeMesa, PDO::PARAM_INT);
            $consulta->bindValue(':puntajeRestorant', $this->puntajeRestorant, PDO::PARAM_INT);
            $consulta->bindValue(':puntajeCocinero', $this->puntajeCocinero, PDO::PARAM_INT);
            $consulta->bindValue(':puntajeMozo', $this->puntajeMozo, PDO::PARAM_INT);
            $consulta->execute(); 
            return $accesoDatos->obtenerUltimoId();
        }

        public static function obtenerTodas()
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM encuestas");
            $consulta->execute(); 
            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
        }

        public static function obtenerEncuesta($id)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM encuestas WHERE id = :id");
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->fetchObject('Encuesta');
        }

        public static function modificarEncuesta($id)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta();
            $consulta->bindValue();
            $consulta->execute();
            return $consulta->rowCount();
        }

        public static function borrarEncuesta($id)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("DELETE FROM encuestas WHERE id = :id");
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->rowCount();
        }
    }

?>