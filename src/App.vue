<template>
  <div id="app">
    <div id="nav">
      <router-link to="/consumos"
        >Generar Consumos
        <span>
          ><router-link to="/consumos/lista">Listar Consumos </router-link>
        </span>
        <span>
          ><router-link to="/consumos/estadistica"
            >Estadistica Consumos</router-link
          >
        </span>
      </router-link>
      |
      <router-link to="/solicitud"
        >Agregar solicitud
        <span>
          ><router-link to="/solicitud/lista">Lista de solicitudes</router-link>
        </span>
        <span>
          ><router-link to="/solicitud/lista/pedidos">Lista de Pedidos</router-link>
        </span>
      </router-link>
      |
      <router-link to="/addope">Operarios</router-link> |
      <router-link to="/addsec">Sector</router-link> |
      <router-link to="/addher">Herramientas</router-link> |
      <router-link to="/about">About</router-link>
    </div>
    <router-view />
  </div>
</template>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

#nav {
  padding: 30px;
}

#nav a {
  font-weight: bold;
  color: #2c3e50;
}

#nav a.router-link-exact-active {
  color: #42b983;
}
#nav a span {
  visibility: hidden;
  display: none;
}
#nav a.router-link-active span {
  visibility: visible;
  display: inline;
}
</style>
