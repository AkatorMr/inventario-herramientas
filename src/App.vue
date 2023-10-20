<template>
  <div id="app">
    <PopUpMsg ref="PopUpMsgs"></PopUpMsg>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="nav2">
      <a class="navbar-brand" href="#">Navbar</a>

      <ul class="navbar-nav mr-auto">
        <li class="nav-item"><router-link class="nav-link" to="/consumos">Generar Consumos</router-link></li>
        <li class="nav-item"><router-link class="nav-link" to="/solicitud">Agregar solicitud</router-link></li>
        <li class="nav-item"><router-link class="nav-link" to="/addope">Operarios</router-link> </li>
        <li class="nav-item"><router-link class="nav-link" to="/addsec">Sector</router-link></li>
      </ul>

    </nav>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="nav3">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item"><span>
            <router-link class="nav-link" to="/consumos">
              <router-link class="nav-link" to="/consumos/lista">Listar Consumos </router-link>
            </router-link>
          </span></li>
        <li class="nav-item">
          <span>
            <router-link class="nav-link" to="/consumos">
              <router-link class="nav-link" to="/consumos/estadistica">Estadistica
                Consumos</router-link>
            </router-link>
          </span>
        </li>
        <li class="nav-item"><span><router-link class="nav-link" to="/solicitud"><router-link class="nav-link"
                to="/solicitud/lista">Lista de
                solicitudes</router-link></router-link></span></li>
        <li class="nav-item">
          <span>
              <router-link class="nav-link" to="/solicitud/lista">
                <span>
                  <router-link class="nav-link" to="/solicitud/lista/pedidos">Lista de
                    Pedidos</router-link>
                  </span>

            </router-link>
          </span>
        </li>

      </ul>

    </nav>


    <div id="nav">
      <router-link to="/consumos">Generar Consumos
        <span>
          ><router-link to="/consumos/lista">Listar Consumos </router-link>
        </span>
        <span>
          ><router-link to="/consumos/estadistica">Estadistica Consumos</router-link>
        </span>
      </router-link>
      |
      <router-link to="/solicitud">Agregar solicitud
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

<script>
import PopUpMsg from "./components/PopUpMsg.vue";

export default {
  name: "CargarOperarios",
  components: {
    PopUpMsg
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

#nav3 {
  padding-left: 12em;
  padding-right: auto;
}

span>.nav-link {
  display: none;
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
