# Roadmap

## Cosas por hacer - Liste de features:

- [x] Formulario para editar operarios ya cargados
- [ ] Formulario para editar descripción de códigos - ahora se hace de manera implicita.
- [x] Botones para modficiar el estado de las solicitudes
- [ ] Agregar acción a los botones de solicitud de estado
  - [ ] Función Confirmar
  - [ ] Función Editar
  - [x] Función Eliminar
- [x] Agregar boton para ver las solicitudes eliminadas
- [ ] En lista de consumos, agregar comando para pasar de un operario a otro
- [ ] Agregar forma de cargar muchos consumos de una sola vez, en tandem o bach o con un archivo csv
- [ ] Seleccionar las solicitudes y cambiarles el estado a "Para Pedir"
- [o] Generar vista donde se agrupan las solicitudes con el mismo pedido
- [x] Generar una vista que muestre todos los pedidos, con la opción de ver detalle de los mismos
- [x] Seleccionar solicitud y cargarle un pedido
- [x] Agregar filtro a las solicitudes por estado.
- [ ] Agregar seguridad a la hora de cargar consumos, algo que relacione el nombre con el legajo como las primeras letras del nombre.
- [ ] Agregar notificaciones de las consultas con el servidor
- [x] Revisar el "Marcar como disponible" de las solicitudes, parece que no anda.
- [ ] Agregar botón "Ordenar Por" en las tablas.
- [ ] Agregar cierre de caja, por semana, mes y año
- [x] En la vista de las solicitudes, separar el "ESTADO" en pestañas.
- [x] Quitar referencias de OT y Nro de vale de la página generar consumo.
- [x] Revisar el cambio de estado de la solicitud a Lista y Cargado porque no actualiza esos valores.
- [x] Al apretar Enter en los filtros que se active el botón para no usar el Mouse 
- [x] La búsqueda de los filtros debe ser caseinsensitive
- [x] Agregar a los filtros un comodin % para filtrar
- [ ] En la lista de solicitudes al modificar el estado de una solicitud hacerlo con todos los items de la misma
- [ ] Pantalla para crear solicitudes, que listen las solicitudes
- [ ] Al crear la solicitudes mostras los códigos que ya se encuentran en solicitudes.
- [ ] Cuando se filtra por nombre y se usa acento no se obtienen resultados



## Cambio de perspectiva

Para generar una herramienta de gestión de herramientas que sea útil, necesitas modificar el funcionamiento para que no solo te permita registrar sino que tambíen te permita organizarte de una forma práctica.

Una posible idea es que el retiro de una herramienta solo se permita cuando tenga un pedido y que cada pedido se origine desde una solicitud.

Por lo que el flujo de información será:

Solicitud ---> Pedido ---> Entrega

Al hacer las solicitudes, mostrar las solicitudes que se encuentran Pedidas y con Entrega

Cada elemnto debe terner una lista y un botón de crear.

### Solicitudes:

- [ ] Crear solicitud -> Ir a formulario para cargar la o las solicitudes
- [ ] En el formulario de cargar de solicitudes un botón para crear pedido
- [ ] Lista de solicitudes con el botón de crear pedido
- [ ] El botón de crear pedido debe ir a un formulario para crear los pedidos

### Pedido:

- [ ] Crear pedido desde solicitudes - Muestra una lista de solicitudes para crear pedido
- [ ] Lista de pedidos realizados, muestra fecha, estado de pedido
- [ ] 

### Engtrega: 

Esta parte es lo de consumo que se debe cambiar la manera de mostrar y relacionar con la tabla de pedidos

- [ ] 


## Unión de tablas

Consumos (id_solicitud_compra) ------- Pedido ()