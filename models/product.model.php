<?php

class ProductModel
{

    private $db;

    public function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;' . 'dbname=db_tienda;charset=utf8', 'root', '');
    }

    //Recibe los parametros
    public function getAll($parametros)
    {
        // 1. abro conexión a la DB
        // ya esta abierta por el constructor de la clase
        //vinculo categoria con producto. LO HAGO PARA MOSTRAR LAS CATEGORIAS EN TODOS LOS PRODUCTOS. 
        $limit = $parametros['limit'];
        $offset = $parametros['page'] * $parametros['limit'] - $parametros['limit']; //Es igual a la pagina x el limite menos el limite. Entonces muestra la cantidad de elementos dados x el limite a partir de el N elemento dado.

        $query = $this->db->prepare("SELECT productos.*, categorias.categoria FROM productos JOIN categorias 
                ON productos.id_categoria = categorias.id
                ORDER BY " . $parametros['sortBy'] . " " . $parametros['order'] .  "
                LIMIT " . $limit . "
                OFFSET " . $offset . "
        ");
        $query->execute();

        // 3. obtengo los resultados
        $products = $query->fetchAll(PDO::FETCH_OBJ);

        return $products;
    }

    public function getAllFilter($parametros)
    {
        // 1. abro conexión a la DB
        // ya esta abierta por el constructor de la clase
        //vinculo categoria con producto. LO HAGO PARA MOSTRAR LAS CATEGORIAS EN TODOS LOS PRODUCTOS. 
        $limit = $parametros['limit'];
        $offset = $parametros['page'] * $parametros['limit'] - $parametros['limit'];

        $query = $this->db->prepare("SELECT productos.*, categorias.categoria FROM productos JOIN categorias
                ON productos.id_categoria = categorias.id
                WHERE " . $parametros['filter'] . " = ? 
                ORDER BY " . $parametros['sortBy'] . " " . $parametros['order'] .  "
                LIMIT " . $limit . "
                OFFSET " . $offset . "
        ");
        $query->execute([$parametros['value']]);

        // 3. obtengo los resultados
        $products = $query->fetchAll(PDO::FETCH_OBJ);

        return $products;
    }

    public function get($id)
    {

        $query = $this->db->prepare("SELECT productos.*, categorias.categoria FROM productos JOIN categorias ON productos.id_categoria = categorias.id WHERE productos.id = ?"); // Join (agrego productos.id para seleccionar ese id) LO HAGO PARA MOSTRAR LA CATEGORIA EN EL DETALLE.
        $query->execute([$id]);

        $product = $query->fetch(PDO::FETCH_OBJ);
        return $product;
    }


    public function getProductsByCategory($id)
    {
        $query = $this->db->prepare("SELECT * FROM productos WHERE id_categoria = ?");
        $query->execute([$id]);

        $productByCategory = $query->fetchAll(PDO::FETCH_OBJ);  //LE PONGO FETCH ALL PORQUE TRAE VARIOS.
        return $productByCategory;
    }

    public function insert($modelo, $marca, $precio, $categoria, $imagen = null)
    {
        $pathImg = '';
        if ($imagen)        //Si hay imagen que la muestre, sino q muestre el campo vacio.
            $pathImg = $this->uploadImage($imagen);

        $query = $this->db->prepare("INSERT INTO productos (modelo, marca, precio, id_categoria, imagen) VALUES (?, ?, ?, ?,?)");
        $query->execute([$modelo, $marca, $precio, $categoria, $pathImg]);

        return $this->db->lastInsertId();
    }

    //AGREGO ESTE METODO PRIVADO PARA INSERTAR IMAGENES
    private function uploadImage($image)
    {
        $target = 'uploads/' . uniqid() . '.jpg'; //esta funcion nos genera un id que es unico, por si hay img con mismo nombre cuando se ingresa
        move_uploaded_file($image, $target);
        return $target;
    }


    function delete($id)
    {
        $query = $this->db->prepare('DELETE FROM productos WHERE id = ?');
        $query->execute([$id]);
    }
}
