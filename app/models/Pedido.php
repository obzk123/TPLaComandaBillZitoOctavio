<?php

    require_once("E:/xampp/htdocs/programacion_3/Trabajo-practico-LaComanda/app/db/AccesoDatos.php");

    class Pedido
    {
        public $id;
        public $producto;
        public $mesa;
        public $estado;

        public function CrearPedido()
        {
            $acessoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $acessoDatos->prepararConsulta("INSERT INTO pedidos (producto, mesa, estado) values (:producto, :mesa, :estado)");
            $consulta->bindValue(':producto', $this->producto, PDO::PARAM_STR);
            $consulta->bindValue(':mesa', $this->mesa, PDO::PARAM_INT);
            $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
            $consulta->execute();
            return $acessoDatos->obtenerUltimoId();
        }

        public static function ObtenerPedido($id)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE id = :id");
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->fetchObject('Pedido');
        }

        public static function ObtenerPedidos()
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM pedidos");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");
        }

        public static function ModificarPedido($pedido)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("UPDATE pedidos SET producto = :producto, mesa = :mesa, estado = :estado WHERE id = :id");
            $consulta->bindValues(':id', $pedido->id, PDO::PARAM_INT);
            $consulta->bindValue(':producto', $pedido->producto, PDO::PARAM_STR);
            $consulta->bindValue(':mesa', $pedido->mesa, PDO::PARAM_INT);
            $consulta->bindValue(':estado', $pedido->estado, PDO::PARAM_STR);
            $consulta->execute();
            return $consulta->rowCount();
        }

        public static function EliminarPedido($id)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("DELETE FROM pedidos WHERE id = :id");
            $consulta->bindValues(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->rowCount();
        }
    }


?>