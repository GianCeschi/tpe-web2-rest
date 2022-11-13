<?php
require_once './libs/router.php';
require_once 'controllers/product-api.controller.php'; //NO OLVIDAR DE REQUERIR EL CONTROLLER


// creo el router
$router = new Router();

//defino tabla de ruteo
$router->addRoute('products', 'GET', 'ProductApiController','getProducts');
$router->addRoute('products/:ID', 'GET', 'ProductApiController', 'getProduct');
$router->addRoute('products/:ID', 'DELETE', 'ProductApiController', 'deleteProduct');
$router->addRoute('products', 'POST', 'ProductApiController', 'insertProduct'); 

// ejecuta la ruta (sea cual sea)
$router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);