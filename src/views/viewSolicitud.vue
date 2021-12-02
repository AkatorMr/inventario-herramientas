<template>
  <div class="container">
    <div class="MuroDeCarga" v-if="bMuroDeCarga"></div>
    <input-drop-down
      :prompt="'Legajo o Nombre'"
      :lista_completa="lista_operarios"
      @onchange="
        (val) => {
          tNombre = val;
        }
      "
    ></input-drop-down>

    <div class="input-group mb-3">
      <span class="input-group-text col-md-2">Código y Descripción:</span>
      <input
        type="text"
        class="form-control col-md-1"
        placeholder="Código"
        aria-label="Código"
        v-model="tCodigo"
        @keyup="FiltrarHerramienta()"
        @focus="bFocusCodigoODescripcion = true"
        @blur="bFocusCodigoODescripcion = false"
      />

      <input
        type="text"
        class="form-control col-md"
        style="width: 35%"
        placeholder="Descripción"
        aria-label="Descripción"
        v-model="tDescripcion"
        @keyup="FiltrarHerramienta()"
        @focus="bFocusCodigoODescripcion = true"
        @blur="bFocusCodigoODescripcion = false"
      />

      <ul
        :class="
          'dropdown-menu ' +
          (lista_codigos_filtro.length > 0 && bFocusCodigoODescripcion
            ? 'show'
            : '')
        "
        style="
          position: absolute;
          inset: 0px auto auto 0px;
          margin: 0px;
          transform: translate(185px, 40px);
        "
      >
        <li>
          <a
            class="dropdown-item"
            href="#"
            v-for="(item, key) in lista_codigos_filtro"
            :key="key"
            @mousedown="
              tCodigo = item.Codigo;
              tDescripcion = item.Descripcion;
              FiltrarHerramienta();
            "
            >{{ item.Codigo }} | {{ item.Descripcion }}</a
          >
        </li>
        <li v-if="lista_codigos_filtro.length == 11">
          <a class="dropdown-item">......</a>
        </li>
      </ul>

      <span class="input-group-text col-md-1">Cantidad:</span>

      <input
        class="form-control col-md"
        placeholder="Cantidad"
        aria-label="Cantidad"
        v-model="tCantidad"
        type="number"
      />
    </div>
  </div>
</template>

<script>

import InputDropDown from "../components/InputDropDown.vue";

export default {
  name: 'viewSolicitud',
  components: {
    InputDropDown
  },data() {
    return {
      lista_sectores:[],
      lista_operarios:[],
      tNombre:"",bMuroDeCarga:false

    }
  },
  
  methods: {
    ListarSectores: function(){
      let that = this;
      fetch('/api/index.php?ListarSectores').then(response => response.json()).then(resp=>that.lista_sectores=resp);
  
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
      formData.append("valeoracle",that.tVale.Oracle);
      formData.append("mpot",that.tVale.MPot);
      formData.append("mpvale",that.tVale.MPvale);
      console.log(formData);
      //formData.append("nombre", that.op_nombre);
      //formData.append("sector", that.op_sector);
      // request options
      const options = {
        method: "POST",
        body: formData,
      };

      // send POST request
      fetch("/api/index.php?NuevaSolicitud", options)
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
      this.tNombre = "";
      this.tCantidad = 0;
      this.tVale.Oracle=null;
      this.tVale.MPvale=null;
      this.tVale.MPot=null;
    },
    ListarOperarios: function () {
      let that = this;
      fetch("/api/index.php?ListarOperarios")
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
    
  },
  mounted() {
    this.ListarOperarios();
  },
}
</script>
<style scoped>
.MuroDeCarga{
   position:absolute;
  left: 0;
  width: 100%;
  height: 100%;
  top: 0;
  background-color: rgba(0, 102, 128, 0.527);
  z-index: 99999;
}
</style>