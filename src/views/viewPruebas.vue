<template>
  <div class="container">
    <div class="Menu">
      <div>Menu</div>
      <div>Submenu</div>
      <div>Herramientas</div>
      <div>Cuatro</div>
    </div>

    <div>
      <span>Agregar</span>
      <span>Agregar Otro Igual</span>
      <span>Editar</span>
      <span>Editar Selección</span>
      <span>Eliminar</span>
      <span>|</span>
      <span>Seleccionar todo</span>
      <span>Grupos</span>
      <span>Importar/Exportar</span>
      <span>Ayuda</span>
    </div>
  </div>
</template>

<style scoped>
.back {
  background-color: teal;
}
.Menu {
  /* width: 400px; */

  margin: auto;
  position: relative;
  padding: 4px;
}

.Menu div {
  background-color: rgb(238, 238, 238);

  border-width: 2px;
  border-style: solid;
  border-color: white;

  display: inline-block;
}
.Menu div:nth-child(1) {
  width: 60px;
}
.Menu div:nth-child(2) {
  width: 60px;
}
.Menu div:nth-child(3) {
  width: 100%;
}
.Menu div:nth-child(4) {
  width: 40px;
}
</style>
