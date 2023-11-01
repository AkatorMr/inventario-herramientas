<template>
  <div id="app">
    <PopUpMsg ref="PopUpMsgs"></PopUpMsg>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="nav2">
      <a class="navbar-brand" href="#">Navbar</a>

      <ul class="navbar-nav mr-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle">Consumos</a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <router-link class="dropdown-item" to="/consumos">Generar Consumos</router-link>
            <router-link class="dropdown-item" to="/consumos/lista">Listar Consumos</router-link>
            <router-link class="dropdown-item" to="/consumos/estadistica">Estadistica Consumos</router-link>
          </div>

        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle">Solicitudes</a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <router-link class="dropdown-item" to="/solicitud">Agregar solicitud</router-link>
            <router-link class="dropdown-item" to="/solicitud/lista">Lista de
              solicitudes</router-link>
            <router-link class="dropdown-item" to="/solicitud/lista/pedidos">Lista de
              Pedidos</router-link>
          </div>

        </li>


        <li class="nav-item"><router-link class="nav-link" to="/addope">Operarios</router-link> </li>
        <li class="nav-item"><router-link class="nav-link" to="/addsec">Sector</router-link></li>

      </ul>

    </nav>

      <router-view />

  </div>
</template>

<script>
import PopUpMsg from "./components/PopUpMsg.vue";

export default {
  name: "CargarOperarios",
  components: {
    PopUpMsg
  },
  data() {
    return {
      scon: false
    }
  }
}
</script>
<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

#nav,
#nav2 {
  padding: 30px 30px 0px 30px;
}

#nav a {
  font-weight: bold;
  color: #2c3e50;
}

.nav-link.router-link-exact-active {
  font-style: oblique;
}

span .nav-link.router-link-active {
  font-style: oblique;
  display: contents;
}

#nav2 {
  /* padding-left: 12em;
  padding-right: auto; */
  margin-bottom: 2em;
}


#nav a.router-link-exact-active {
  color: #42b983;
}


#nav a.router-link-active span {
  visibility: visible;
  display: inline;
}

.nav-item.dropdown:hover>div {
  display: block;
}
</style>
