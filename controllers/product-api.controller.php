<?php

require_once 'models/product.model.php';
require_once 'views/api.view.php';

class ProductApiController
{
    private $model;
    private $view;

    private $data;

    public function __construct()
    {
        $this->model = new ProductModel();
        $this->view = new ApiView();

        //lee el body del request
        $this->data = file_get_contents("php://input"); //saco el body del request.
    }

    private function getData()
    {
        return json_decode($this->data); //Transforma lo que esta en data a JSON.
    }

    public function getProducts($params = null)
    {

        $parametros = []; //Arreglo vacio que se va llenando si existen cosas en el GET.
        //Ordenado por defecto
        $parametros['sortBy'] = $_GET['sortBy'] ?? "precio";
        $parametros['order'] = $_GET['order'] ?? "asc";
        //Paginado por defecto
        $parametros['page'] = $_GET['page'] ?? 1;
        $parametros['limit'] = $_GET['limit'] ?? 5;
        //Filtrado por defecto
        $parametros['filter'] = $_GET['filter'] ?? null;
        $parametros['value'] = $_GET['value'] ?? null;

        $columnas = array("modelo", "marca", "precio", "id_categoria", "imagen"); //Para que pueda ordenar por una columna valida
        if (isset($_GET['sortBy'])) {
            $parametros['sortBy'] = $_GET['sortBy'];
            if ((in_array($parametros['sortBy'], $columnas))) {
                if (isset($_GET['order']) && ($_GET['order'] == 'asc') || ($_GET['order'] == 'desc')) {
                    $parametros['order'] = $_GET['order'];
                } else {
                    $this->view->response("Debe ordenar los productos solo de forma 'asc' o 'desc ", 400);
                    die();
                }
            } else {
                $this->view->response("Debe ingresar una columna valida en la tabla de productos", 400);
                die();
            }
        }
        if (isset($_GET['filter'])) {
            if ($_GET['filter'] == "categoria") {
                $parametros['filter'] = $_GET['filter'];
                if (isset($_GET['value'])) {
                    $parametros['value'] = $_GET['value'];
                }
            } else {
                $this->view->response("Solo puede filtrar por categoria", 400);
                die();
            }
        }
        if (isset($_GET['page'])) {
            if ((is_numeric($_GET['page'])) && ($_GET['page'] > 0)) {
                $parametros['page'] = $_GET['page'];
            } else {
                $this->view->response("Debe ingresar un valor numerico y mayor a 0", 400);
                die();
            }
        }
        if (isset($_GET['limit'])) {
            if ((is_numeric($_GET['limit'])) && ($_GET['limit'] > 0)) {
                $parametros['limit'] = $_GET['limit'];
            } else {
                $this->view->response("Debe ingresar un valor numerico y mayor a 0 ", 400);
                die();
            }
        }
        if(empty($parametros['filter'])){
            $products = $this->model->getAll($parametros); //Le paso los parametros del GET.
        }else{
            $products = $this->model->getAllFilter($parametros);
        }
        if (empty($products)) return  $this->view->response("El arreglo esta vacio", 204); // Si me ponen un booleano o algo que no sea un producto me manda un error 204. VIENE ACA POR EL VALUE 
        $this->view->response($products);
    }

    public function getProduct($params = null)
    {
        // obtengo el id del arreglo de params
        $id = $params[':ID'];
        $product = $this->model->get($id);

        // si no existe devuelvo 404
        if ($product)
            $this->view->response($product);
        else
            $this->view->response("El producto con el id=$id no existe", 404);
    }

    public function deleteProduct($params = null)
    {
        $id = $params[':ID'];

        $product = $this->model->get($id);
        if ($product) {
            $this->model->delete($id);
            $this->view->response($product);
        } else
            $this->view->response("El producto con el id=$id no existe", 404);
    }

    public function insertProduct($params = null)
    {
        $product = $this->getData(); // le hace un JSON_decode a data

        if (empty($product->modelo) || empty($product->marca) || empty($product->precio) || empty($product->id_categoria) || empty($product->imagen)) {
            $this->view->response("Complete los datos", 400);
        } else {
            $id = $this->model->insert($product->modelo, $product->marca, $product->precio, $product->id_categoria, $product->imagen);
            $product = $this->model->get($id);
            $this->view->response($product, 201);
        }
    }

}
