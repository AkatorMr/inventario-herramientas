<template>
  <div class="container">
    <div class="muro" v-if="bMuro" ></div>
    <div class="input-group mb-3">
      <span class="input-group-text col-sm-1" id="basic-addon1">Legajo</span>

      <input
        type="text"
        class="form-control"
        placeholder="Legajo"
        aria-label="Legajo"
        aria-describedby="basic-addon1"
        v-model="op_legajo"
      />
    </div>
    <div class="input-group mb-3">
      <span class="input-group-text col-sm-1" id="basic-addon1">Apellido</span>
      <input
        type="text"
        class="form-control"
        placeholder="Apellido"
        aria-label="Apellido"
        aria-describedby="basic-addon1"
        v-model="op_apellido"
      />
    </div>
    <div class="input-group mb-3">
      <span class="input-group-text col-sm-1" id="basic-addon1">Nombre</span>
      <input
        type="text"
        class="form-control"
        placeholder="Nombre"
        aria-label="Nombre"
        aria-describedby="basic-addon1"
        v-model="op_nombre"
      />
    </div>
    <div class="input-group mb-3">
      <span class="input-group-text col-sm-1" id="basic-addon1">Sector</span>
      <input
        type="text"
        class="form-control"
        placeholder="Sector"
        aria-label="Sector"
        aria-describedby="basic-addon1"
        v-model="op_sector"
        @keyup="FiltrarSector()"
        @focus="bFocusSector=true;"
        @blur="bFocusSector=false;"
      />
      <ul
        :class="
          'dropdown-menu ' + (lista_sectores_filtro.length > 0 && bFocusSector ? 'show' : '')
        "
        aria-labelledby="dropdownMenuButton1"
        style="
          position: absolute;
          inset: 0px auto auto 0px;
          margin: 0px;
          transform: translate(95px, 40px);
        "
      >
        <li>
          <a
            class="dropdown-item"
            href="#"
            v-for="(item, key) in lista_sectores_filtro"
            :key="key"
            @click="op_sector = item;FiltrarSector();"
            >{{ item }}</a
          >
        </li>
      </ul>

    </div>

    <button class="btn btn-outline-primary" @click="InsertarOperario()">Insertar</button>
    <lista-operarios></lista-operarios>
  </div>
</template>

<script>
import ListaOperarios from "../components/ListaOperarios.vue";

export default {
  name: "CargarOperarios",
  components: {
    ListaOperarios,
  },
  data() {
    return {
      op_legajo: "",
      op_apellido: "",
      op_sector: "",
      op_nombre: "",
      lista_sectores: [],
      lista_sectores_filtro: [],
      bMuro: false,
      bFocusSector:false
    };
  },
  methods: {
    ListarSectores: function () {
      console.log("Text");
      let that = this;
      fetch("/api/index.php?ListarSectores")
        .then((response) => response.json())
        .then((resp) => (that.lista_sectores=resp));
    },
    FiltrarSector: function () {
      this.lista_sectores_filtro = [];
      for (let item in this.lista_sectores) {
        
        if (this.lista_sectores[item].Sector.includes(this.op_sector) || this.op_sector=="") {
          this.lista_sectores_filtro.push(this.lista_sectores[item].Sector);
        }
      }
    },
    CambiarSector: function (sour) {
      console.log(sour);
    },
    DatosRecibidos: function (data) {
      console.log(data);
      this.bMuro = false;
    },
    InsertarOperario: function () {
      this.bMuro = true;
      let that = this;

      var formData = new FormData();
      formData.append("legajo", that.op_legajo);
      formData.append("apellido", that.op_apellido);
      formData.append("nombre", that.op_nombre);
      formData.append("sector", that.op_sector);
      // request options
      const options = {
        method: "POST",
        body: formData,
      };

      // send POST request
      fetch("/api/index.php?InsertarOperario", options)
        .then((res) => res.text())
        .then((res) => that.DatosRecibidos(res));
    },
    
  
  },
  mounted() {
      this.ListarSectores();
      this.FiltrarSector();
      
    }
};
</script>

<style scoped>
.muro{
  position:absolute;
  left: 0;
  width: 100%;
  height: 100%;
  top: 0;
  background-color: rgba(0, 102, 128, 0.527);
  z-index: 99999;
}
</style>