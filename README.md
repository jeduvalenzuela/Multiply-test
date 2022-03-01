# Multiply-test

Proyecto para presentar la funcionalidad del plugins "films-api" creado para visualizar el ranking de peliculas y series de tv.
Consume datos de Api Rest de https://imdb-api.com/

-En la instalación del plugin crea la tabla "wishlist" en la base de datos y crea tambien dos roles de usuario nuevos (Amante de Pelis y Amante de Series).
-Para iniciar el pugin hay que crear previamente las paginas ./films y ./favoritos en las cuales se proyectan los datos obtenidos mediante api.

En la vista FILMS (estando logueado) se puede gardar en favoritos los titulos seleccionados.
En la vista favoristos se pueden borrar los titulos de las lista de favoritos.

El listado de favoritos son tabajados en una tabla "wishlist" creada precisamente para almacenar el registro de favoritos. En esta se almacena el ID de la pelicula utilizado en la API de IMDB y el id del usuario de Wordpress.
