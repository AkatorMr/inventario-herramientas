<template>
  <div class="container">
    <div class="MuroDeCarga" v-if="bIngresoDatos">
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
    </div>
    <InputBox
      v-if="bShowInputBox"
      :Titulo="'Legajo'"
      :Default="valoramostrar"
      @Listo="Resultado"
    ></InputBox>

    <table class="table">
      <thead>
        <tr>
          <th scope="col">
            Legajo
            <div @click="Filtro('Legajo')">
              {{ filtro_legajo == "" ? "#######" : filtro_legajo }}
            </div>
          </th>
          <th scope="col">
            Nombre y Apellido
            <div @click="Filtro('Nombre')">
              {{ filtro_nombre == "" ? "#######" : filtro_nombre }}
            </div>
          </th>
          <th scope="col">
            Código
            <div @click="Filtro('Codigo')">
              {{ filtro_codigo == "" ? "#######" : filtro_codigo }}
            </div>
          </th>
          <th scope="col">
            Descripción
            <div @click="Filtro('Descripcion')">
              {{ filtro_descripcion == "" ? "#######" : filtro_descripcion }}
            </div>
          </th>
          <th scope="col">Estado</th>
          <th scope="col">Comandos</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(a, key) of lista_solicitudes" :key="key">
          <th scope="row">{{ a.Legajo }}</th>
          <td>{{ a.full_name }}</td>
          <td>{{ a.cod_herramienta }}</td>
          <td>{{ a.Descripcion }}</td>
          <td>
            {{ a.estado }}
          </td>
          <td>
            <select
              class="form-select mb-6"
              aria-label=""
              @change="alCambiar($event, a.id)"
            >
              <option
                v-for="(item, index) in [
                  'LISTA',
                  'PERDIDA',
                  'CARGADO',
                  'DISPONIBLE',
                  'LLEGO',
                  'GENERAR',
                  'AGREGAR',
                  'CONSUMIDA',
                ]"
                :key="index"
                :selected="item == a.estado"
              >
                {{ item }}
              </option>
            </select>
          </td>
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
      lista_solicitudes: [],
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
      valoramostrar: "",
      id_solicitud: 0,
      nuevo_estado: "AGREGAR",
      nro_solicitud: "",
      nueva_fecha: "",
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
      this.ListarSolicitudes();
      this.bShowInputBox = false;
    },
    Filtro: function (columna) {
      this.sColumna = columna;
      switch (this.sColumna) {
        case "Legajo":
          this.valoramostrar = this.filtro_legajo;

          break;
        case "Nombre":
          this.valoramostrar = this.filtro_nombre;

          break;
        case "Codigo":
          this.valoramostrar = this.filtro_codigo;

          break;
        case "Descripcion":
          this.valoramostrar = this.filtro_descripcion;

          break;
      }

      this.bShowInputBox = true;
    },
    enviarClick: function () {
      let that = this;

      var formData = new FormData();
      formData.append("id_sol", that.id_solicitud);
      formData.append("estado", that.nuevo_estado);
      formData.append("nro_solicitud", that.nro_solicitud);
      formData.append("fecha", that.nueva_fecha);
      
      // request options
      const options = {
        method: "POST",
        body: formData,
      };

      // send POST request
      fetch("/api/index.php?ActualizarSolicitud", options)
        .then((res) => res.text())
        .then((res) => that.DatosSolicitudRecibidos(res));

    },
    DatosSolicitudRecibidos: function (datos) {
      this.bIngresoDatos = false;
      this.nuevo_estado="";
      this.id_solicitud =-1;
      console.log(datos);
    },
    alCambiar: function (event, id_sol) {
      console.log(event.target.value, id_sol);
      this.nuevo_estado=event.target.value;
      this.id_solicitud =id_sol;
      this.bIngresoDatos = true;
    },

    AnteriorNivel: function () {
      this.nivel--;
      this.ListarSolicitudes();
    },
    SiguienteNivel: function () {
      this.nivel++;
      this.ListarSolicitudes();
    },
    ListarSolicitudes: function () {
      let that = this;
      console.log("/api/index.php?ListarSolicitudes&nivel=" + (this.nivel - 1));

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
        "/api/index.php?ListarSolicitudes&nivel=" + (this.nivel - 1),
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
    this.ListarSolicitudes();
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
</style>