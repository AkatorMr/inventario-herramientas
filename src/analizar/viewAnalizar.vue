<template>
  <div class="container">
    <MuroDeCarga :bMostrar="bIngresoDatos">
      <div class="Recuadro">
        <div class="text-center">
          <span>Nro Solicitud</span>
          <input class="form-control" v-model="nro_solicitud" />
        </div>
        <div class="text-center">
          <span>Fecha</span>
          <input class="form-control" type="date" v-model="nueva_fecha" />
        </div>
        <div class="text-center">
          <button class="btn btn-primary" @click="enviarClick">Aceptar</button>
        </div>
      </div>
    </MuroDeCarga>

    <!--  <InputBox
      v-if="bShowInputBox"
      :Titulo="'Legajo'"
      :Default="valoramostrar"
      @Listo="Resultado"
    ></InputBox> -->

    <div :is="currentComponent"></div>
    <button @click="swapComponent">Swap Components</button>
  </div>
</template>

<script>
import InputBox from "../components/InputBox.vue";
import MuroDeCarga from "../components/MuroDeCarga.vue";
import Herramienta from "./Herramieta.vue";
import Operario from "./Operario.vue";

export default {
  name: "Analizar",
  components: { InputBox, MuroDeCarga, Herramienta, Operario },
  data() {
    return {
      lista_solicitudes: [],
      lista_solicitudes_filtro: [],
      bMuro: false,
      bFocusSector: false,
      nivel: 1,
      Accionar: "",
      bIngresoDatos: false,
      bShowInputBox: false,

      valoramostrar: "",
      id_solicitud: 0,
      nuevo_estado: "AGREGAR",
      nro_solicitud: "",
      nueva_fecha: "",
      currentComponent: Herramienta,
    };
  },
  methods: {
    swapComponent: function () {
      if (this.currentComponent == Herramienta) {
        this.currentComponent = Operario;
      } else {
        this.currentComponent = Herramienta;
      }
    },

    enviarClick: function () {
      let that = this;
      this.bIngresoDatos = false;
      return;
    },
    DatosSolicitudRecibidos: function (datos) {
      this.bIngresoDatos = false;
      this.nuevo_estado = "";
      this.id_solicitud = -1;
      console.log(datos);
    },
    ListarSolicitudes: function () {
      let that = this;
      console.log("/api/ListarSolicitudes?nivel=" + (this.nivel - 1));

      var formData = new FormData();

      formData.append("legajo", that.filtro_legajo);
      formData.append("codigo", that.filtro_codigo);
      formData.append("nombre", that.filtro_nombre);
      formData.append("descripcion", that.filtro_descripcion);
      //console.log(formData);
      //formData.append("nombre", that.op_nombre);
      //formData.append("sector", that.op_sector);
      // request options
      const options = {
        method: "POST",
        body: formData,
      };

      fetch(
        "/api/ListarSolicitudes?nivel=" + (this.nivel - 1),
        options
      )
        .then((response) => response.json())
        .then((resp) => {
          /* console.log(resp); */ that.lista_solicitudes = resp;
        });
    },
    FiltrarSector: function () {
      this.lista_sectores_filtro = [];
      for (let item in this.lista_sectores) {
        if (
          this.lista_sectores[item].Sector.includes(this.op_sector) ||
          this.op_sector == ""
        ) {
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
    //this.ListarSolicitudes();
  },
};
</script>

<style scoped>
.Recuadro {
  margin-left: auto;
  margin-right: auto;
  margin-top: 10%;
  margin-bottom: auto;
  width: 250px;
  height: 410px;
  border-radius: 5px;
  background-color: rgba(0, 204, 255);
  z-index: 99991;
  border: solid 1px black;
}
.Recuadro .form-control {
  width: 90% !important;
  margin: auto;
  margin-bottom: 5px;
}
</style>