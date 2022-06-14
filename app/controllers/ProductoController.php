<?php

    require_once "E:/xampp/htdocs/programacion_3/Trabajo-practico-LaComanda/app/models/Producto.php";
    require_once "E:/xampp/htdocs/programacion_3/Trabajo-practico-LaComanda/app/interfaces/IApiUsable.php";

    class ProductoController extends Producto implements IApiUsable
    {
        public function TraerUno($request, $response, $args)
        {
            // Buscamos producto por id
            $id = $args['id'];
            $producto = Producto::ObtenerProducto($id);
            if($producto != null)
            {
                $payload = json_encode($producto);
                $response->getBody()->write($payload);
            }
            else
            {
                $response->getBody()->write("Producto no encontrado");
            }

            return $response->withHeader('Content-Type', 'application/json');
        }

        public function TraerTodos($request, $response, $args)
        {
            $lista = Producto::ObtenerProductos();
            $payload = json_encode(array("listaProductos" => $lista));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function CargarUno($request, $response, $args)
        {
            $parametros = $request->getParsedBody();

            $nombre = $parametros['nombre'];
            $tiempo = $parametros['tiempo'];
            $tipo = $parametros['tipo'];
            $precio = $parametros['precio'];
            $stock = $parametros['stock'];
    
            if($nombre == null || $tiempo == null || $tipo == null || $precio == null || $stock == null)
            {
              $response->getBody()->write("Error al recibir los parametros");
              return $response->withHeader('Content-Type', 'application/json');
            }
    
            // Creamos el producto
            $producto = new Producto();
            $producto->nombre = $nombre;
            $producto->tiempo = $tiempo;
            $producto->tipo = $tipo;
            $producto->precio = $precio;
            $producto->stock = $stock;
            $producto->CrearProducto();
    
            $payload = json_encode(array("mensaje" => "Producto creado con exito"));
    
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function BorrarUno($request, $response, $args)
        {
            $id = $args['id'];
      
            if(Producto::EliminarProducto($id))
            {
              $payload = json_encode(array("mensaje" => "Producto borrado con exito"));
            }
            else
            {
              $payload = json_encode(array("mensaje" => "Producto no encontrado"));
            }   
      
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        
        public function ModificarUno($request, $response, $args)
        {
            $body = $request->getBody();
            $parametros = json_decode($body, true);
          
            $id = $parametros['id'];
            $nombre = $parametros['nombre'];
            $tiempo = $parametros['tiempo'];
            $tipo = $parametros['tipo'];
            $precio = $parametros['precio'];
            $stock = $parametros['stock'];
    
            $producto = Producto::ObtenerProducto($id);
            if($producto == null || $nombre == null || $tiempo == null || $tipo == null || $precio == null || $stock == null)
            {
                $response->getBody()->write("Producto no encontrado o parametros incorrectos");
                return $response->withHeader('Content-Type', 'application/json');
            }
    
            $producto->nombre = $nombre;
            $producto->tiempo = $tiempo;
            $producto->tipo = $tipo;
            $producto->precio = $precio;
            $producto->stock = $stock;

            if(Producto::ModificarProducto($producto))
            {
              $payload = json_encode(array("mensaje" => "Producto modificado con exito"));
            }
            else
            {
              $payload = json_encode(array("mensaje" => "El producto no se pudo modificar"));
            }
    
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
    
?>