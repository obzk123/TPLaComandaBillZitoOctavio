<?php

    class Lista
    {
        public $id;
        public $numero_de_pedido;
        public $idProducto;
        public $estado;

        public function CrearLista()
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta('INSERT INTO lista (numero_de_pedido, idProducto, estado) VALUES (:numero_de_pedido, :idProducto, :estado)');
            $consulta->bindValue(':numero_de_pedido', $this->numero_de_pedido, PDO::PARAM_INT);
            $consulta->bindValue(':idProducto', $this->idProducto, PDO::PARAM_INT);
            $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
            $consulta->execute(); 
            
        }

        public static function ObtenerListaDeUnPedido($numero_de_pedido)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM lista WHERE numero_de_pedido = :numero_de_pedido AND estado != :estado");
            $consulta->bindValue(':numero_de_pedido', $numero_de_pedido, PDO::PARAM_INT);
            $consulta->bindValue(':estado', "entregado", PDO::PARAM_STR);
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Lista');
        }

        public static function CambiarEstado($id, $estado)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("UPDATE lista SET estado = :estado WHERE id = :id");
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
            $consulta->execute();
            return $consulta->fetchObject('Lista');
        }

        public static function obtenerListaPorID($id)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM lista WHERE id = :id");
            $consulta->bindValue(':id', $id, PDO::PARAM_STR);
            $consulta->execute();
            return $consulta->fetchObject('Lista');
        }

        public static function obtenerListaPorRol($estado)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM lista WHERE estado = :estado");
            $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Lista');
        }

        public static function obtenerTodas()
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM lista");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Lista');
        }

        public static function obtenerLista($numero_de_pedido)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM lista WHERE numero_de_pedido = :numero_de_pedido");
            $consulta->bindValue(':numero_de_pedido', $numero_de_pedido, PDO::PARAM_INT);
            $consulta->execute(); 
            return $consulta->fetchObject('Lista');
        }

        public static function modificarLista($id)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta();
            $consulta->bindValue();
            $consulta->execute();
            return $consulta->rowCount();
        }

        public static function borrarLista($numero_de_pedido)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("DELETE FROM lista WHERE numero_de_pedido = :numero_de_pedido");
            $consulta->bindValue(':numero_de_pedido', $numero_de_pedido, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->rowCount();
        }
    }

?>