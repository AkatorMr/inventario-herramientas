<template>
  <div class="container">
    <div class="MuroDeCarga" v-if="bMuroDeCarga"></div>

    <input-drop-down :prompt="'Legajo o Nombre'" :lista_completa="lista_operarios" @onchange="(val) => {
        tNombre = val;
      }
      "></input-drop-down>

    <div class="input-group mb-3">
      <span class="input-group-text col-md-2">Código y Descripción:</span>
      <input type="text" class="form-control col-md-1" placeholder="Código" aria-label="Código" v-model="tCodigo"
        @keyup="FiltrarHerramienta()" @focus="bFocusCodigoODescripcion = true" @blur="bFocusCodigoODescripcion = false" />

      <input type="text" class="form-control col-md" style="width: 35%" placeholder="Descripción" aria-label="Descripción"
        v-model="tDescripcion" @keyup="FiltrarHerramienta()" @focus="bFocusCodigoODescripcion = true"
        @blur="bFocusCodigoODescripcion = false" />

      <ul :class="'dropdown-menu ' +
        (lista_codigos_filtro.length > 0 && bFocusCodigoODescripcion
          ? 'show'
          : '')
        " style="
          position: absolute;
          inset: 0px auto auto 0px;
          margin: 0px;
          transform: translate(185px, 40px);
        ">
        <li>
          <a class="dropdown-item" href="#" v-for="(item, key) in lista_codigos_filtro" :key="key" @mousedown="
            tCodigo = item.Codigo;
          tDescripcion = item.Descripcion;
          FiltrarHerramienta();
          ">{{ item.Codigo }} | {{ item.Descripcion }}</a>
        </li>
        <li v-if="lista_codigos_filtro.length == 11">
          <a class="dropdown-item">......</a>
        </li>
      </ul>

      <span class="input-group-text col-md-1">Cantidad:</span>

      <input class="form-control col-md" placeholder="Cantidad" aria-label="Cantidad" v-model="tCantidad" type="number" />

      <button class="btn btn-outline-success col-md-1">+</button>
    </div>

    <div class="input-group mb-3">
      <span class="input-group-text col-md-1">Fecha:</span>
      <input type="date" class="form-control col-md-1" placeholder="dd/mm/yyyy" aria-label="Fecha" v-model="tFecha" />
      <span class="input-group-text col-md-2">Nro Solicitud:</span>
      <input type="number" class="form-control col-md-1" placeholder="##" v-model="tNroSolicitud" />
    </div>
    <button class="btn btn-outline-primary" @click="CargarSolicitud()">
      Cargar Solicitud
    </button>
  </div>
</template>

<script>

import InputDropDown from "../components/InputDropDown.vue";

export default {
  name: 'viewSolicitud',
  components: {
    InputDropDown
  }, data() {
    return {
      lista_sectores: [],
      lista_operarios: [],
      lista_codigos_filtro: [],
      tNombre: "", bMuroDeCarga: false,
      bFocusCodigoODescripcion: true,
      tCodigo: "",
      tCantidad: "",
      tFecha: "",
      tDescripcion: "",
      tNroSolicitud: 0

    }
  },

  methods: {
    ListarSectores: function () {
      let that = this;
      fetch('/api/ListarSectores').then(response => response.json()).then(resp => that.lista_sectores = resp);

    },
    CargarSolicitud: function () {

      let legajo = this.tNombre.split("|")[0];
      legajo = legajo.substring(0, 6);

      this.bMuroDeCarga = true;
      let that = this;

      var formData = new FormData();
      formData.append("legajo", legajo);
      formData.append("codigo", that.tCodigo);
      formData.append("cantidad", that.tCantidad);
      formData.append("fecha", that.tFecha);
      formData.append("nro_solicitud", that.tNroSolicitud);

      console.log(this.tNroSolicitud);
      //formData.append("nombre", that.op_nombre);
      //formData.append("sector", that.op_sector);
      // request options
      const options = {
        method: "POST",
        body: formData,
      };

      // send POST request
      fetch("/api/NuevaSolicitud", options)
        .then((res) => res.json())
        .then((res) => that.DatosRecibidos(res));
    },
    DatosRecibidos: function (da) {
      this.bMuroDeCarga = false;
      console.log(da);
      if (da == "ok") {
        this.LimpiarDatos();
      }
    },
    LimpiarDatos: function () {
      this.tCodigo = "";
      this.tDescripcion = "";
      //this.tNombre = "";
      this.tCantidad = 0;
    },
    ListarOperarios: function () {
      let that = this;
      fetch("/api/ListarOperarios")
        .then((response) => response.json())
        .then(
          (resp) => that.FiltrarOperarios(resp)
          //console.log(resp)
        );
    },
    FiltrarOperarios: function (obj) {
      this.lista_operarios = [];
      let that = this;
      obj.forEach((item) => {
        if (item.legajo != "") {
          //La barra | se usa despues, no borrar. O cambiar en ambos lados. je GenerarConsumo
          that.lista_operarios.push(
            item.legajo + "|" + item.Nombre + " " + item.Apellido
          );
        }
      });
    },
    ListarCódigos: function () {
      let that = this;
      fetch("/api/ListarCodigos")
        .then((response) => response.json())
        .then((resp) => (that.lista_codigos = resp));
    },
    FiltrarHerramienta: function () {
      this.lista_codigos_filtro = [];
      for (let item in this.lista_codigos) {
        let cod = this.lista_codigos[item].Codigo;
        if (cod == null) continue;

        let desc = this.lista_codigos[item].Descripcion;
        if (desc == null) continue;

        if (
          (cod.includes(this.tCodigo.toUpperCase()) || this.tCodigo == "") &&
          (desc.includes(this.tDescripcion.toUpperCase()) ||
            this.tDescripcion == "")
        ) {
          this.lista_codigos_filtro.push({
            Codigo: this.lista_codigos[item].Codigo,
            Descripcion: this.lista_codigos[item].Descripcion,
          });
          if (this.lista_codigos_filtro.length > 10) break;
        }
      }
    },

  },
  mounted() {
    this.ListarOperarios();
    this.ListarCódigos();
  },
}
</script>
<style scoped>
.MuroDeCarga {
  position: absolute;
  left: 0;
  width: 100%;
  height: 100%;
  top: 0;
  background-color: rgba(0, 102, 128, 0.527);
  z-index: 99999;
}
</style>