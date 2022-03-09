<template>
  <div class="container">
    <div class="MuroDeCarga" v-if="bIngresoDatos"></div>
    <InputBox
      v-if="bShowInputBox"
      :Titulo="'Legajo'"
      @Listo="Resultado"
    ></InputBox>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Nro</th>
          <th scope="col">
            <span>Legajo</span>
            <div @click="Filtro('Legajo')">{{(filtro_legajo=="")?'#######':filtro_legajo}} </div>
          </th>
          <th scope="col">
            <span>Nombre y Apellido</span>
            <div @click="Filtro('Nombre')">{{(filtro_nombre=="")?'#######':filtro_nombre}}</div>
          </th>
          <th scope="col">
            <span>Código</span>
            <div @click="Filtro('Codigo')">{{(filtro_codigo=="")?'#######':filtro_codigo}}</div>
          </th>
          <th scope="col">
            <span>Descripción</span>
            <div @click="Filtro('Descripcion')">{{(filtro_descripcion=="")?'#######':filtro_descripcion}}</div>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(a, key) of lista_consumos" :key="key">
          <th>{{ key + (nivel - 1) * 15 + 1 }}</th>
          <td scope="row">{{ a.Legajo }}</td>
          <td>{{ a.Nombre + " " + a.Apellido }}</td>
          <td>{{ a.Codigo }}</td>
          <td>{{ a.Descripcion }}</td>
        </tr>
      </tbody>
    </table>
    <button
      class="btn bt-outline-primary"
      @click="AnteriorNivel"
      :disabled="nivel < 2"
    >
      Anterior
    </button>
    <button class="btn bt-outline-primary" @click="SiguienteNivel">
      Siguiente
    </button>
  </div>
</template>

<script>
import InputBox from "../components/InputBox.vue";

export default {
  name: "CargarOperarios",
  components: { InputBox },
  data() {
    return {
      op_legajo: "",
      op_apellido: "",
      op_sector: "",
      op_nombre: "",
      lista_consumos: [],
      lista_solicitudes_filtro: [],
      bMuro: false,
      bFocusSector: false,
      nivel: 1,
      Accionar: "",
      bIngresoDatos: false,
      bShowInputBox: false,
      filtro_legajo: "",
      filtro_nombre: "",
      filtro_codigo: "",
      filtro_descripcion: "",
    };
  },
  methods: {
    Resultado: function (valor) {
      //Resultado del inputbox
      switch (this.sColumna) {
        case "Legajo":
          this.filtro_legajo = valor;
          this.sColumna = "";
          break;
        case "Nombre":
          this.filtro_nombre = valor;
          this.sColumna = "";
          break;
        case "Codigo":
          this.filtro_codigo = valor;
          this.sColumna = "";
          break;
        case "Descripcion":
          this.filtro_descripcion = valor;
          this.sColumna = "";
          break;
      }
      this.ListarConsumos();
      this.bShowInputBox = false;
    },
    Filtro: function (columna) {
      this.sColumna = columna;
      this.bShowInputBox = true;
    },
    ListarConsumos: function () {
      let that = this;
      //console.log("/api/index.php?ListarConsumos&nivel=" + (this.nivel - 1));

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

      fetch("/api/index.php?ListarConsumos&nivel=" + (this.nivel - 1), options)
        .then((response) => response.json())
        .then((resp) => {
          //that.lista_operarios = resp
          that.lista_consumos = resp;
          //console.log(resp);
        });
    },

    AnteriorNivel: function () {
      this.nivel--;
      this.ListarConsumos();
    },
    SiguienteNivel: function () {
      this.nivel++;
      this.ListarConsumos();
    },

    DatosRecibidos: function (data) {
      console.log(data);
      this.bMuro = false;
    },
  },
  mounted() {
    this.ListarConsumos();
  },
};
</script>

<style scoped>
.muro {
  position: absolute;
  left: 0;
  width: 100%;
  height: 100%;
  top: 0;
  background-color: rgba(0, 102, 128, 0.527);
  z-index: 99999;
}

.MuroDeCarga {
  position: absolute;
  left: 0;
  width: 100%;
  height: 100%;
  top: 0;
  background-color: rgba(0, 102, 128, 0.527);
  z-index: 99999;
}

tr th div {
  cursor: pointer;
}
</style>