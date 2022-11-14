Documentacion API - Tienda DROPS

URL:
http://localhost/tpe-web2-rest/api/

Debe indicar un recurso en la url : http://localhost/tpe-web2-rest/api/recurso

Se debe que usar el recurso ---> products

Si se quiere un producto en especifico debe indicarlo con su id : http://localhost/tpe-web2-rest/api/recurso/id 


FILTERING:
Agregue parámetros de consulta a la solicitud GET:

/products?filter=categoria&value=zapatillas - Se tiene que filtrar por categoria y esta combinado con el valor del parametro value (En este caso quiero que me filtre por zapatillas, pero lo puede hacer por cualquier producto disponible).


SORTING:
Agregue parámetros de consulta a la solicitud GET:

/products?sortBy=precio&order=desc

Nota: Si no se indica nada, hay un valor por defecto que es sortBy=precio y el orden predeterminado será asc


PAGINATION:
Agregue parámetros de consulta a la solicitud GET:

/products?page=1&limit=5

Nota: Si no se indica nada, hay un valor por defecto que es page=1 y limit=5

METODO GET:
Si le agrega un id existente va a obtener un producto especifico:

Por ejemplo:
http://localhost/tpe-web2-rest/api/products/87

METODO POST:
Para insertar un producto nuevo debe ingresar la informacion en formato JSON de la siguiente manera:
 {
        "modelo": "Air Max",
        "marca": "Nike",
        "precio": 95000,
        "id_categoria": 12,
        "imagen": "uploads/634963a64fd3e.jpg"
    }

METODO DELETE:
Para eliminar se debe conocer el id del producto a eliminar:

Por ejemplo:
http://localhost/tpe-web2-rest/api/products/87