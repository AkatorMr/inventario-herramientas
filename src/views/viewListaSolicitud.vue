<template>
  <div class="container">
    <MuroDeCarga :bMostrar="bIngresoDatos" @dismis="bIngresoDatos = false">
      <!-- <div class="MuroDeCarga" v-if="bIngresoDatos"> -->

      <div class="Recuadro" v-if="editar_solicitud.nuevo_estado != 'DISPONIBLE'">
        <div class="text-center">
          <span>Cambiar Estado</span>
          <select class="form-control form-select" aria-label="" v-model="editar_solicitud.nuevo_estado">
            <option v-for="(item, index) in [
              'LISTA',
              'PERDIDA',
              'CARGADO',
              'LLEGO',
              'GENERAR',
              'AGREGAR',
              'CONSUMIDA',
            ]" :key="index">
              {{ item }}
            </option>
          </select>
        </div>
        <div class="text-center">
          <span>Nro Solicitud</span>
          <input class="form-control" v-model="editar_solicitud.nro_solicitud" />
        </div>
        <div class="text-center">
          <span>Fecha</span>
          <input class="form-control" type="date" v-model="editar_solicitud.nueva_fecha" />
        </div>
        <div class="text-center">
          <button class="btn btn-primary" @click="enviarClick">Aceptar</button>
        </div>
      </div>
      <!-- </div> -->
    </MuroDeCarga>

    <MuroDeCarga :bMostrar="sPedido.bMostrar" @dismis="sPedido.bMostrar = false">
      <div class="Recuadro">
        <div class="text-center">
          <span>Registrar en pedido</span>
          <input class="form-control" v-model="sPedido.nro_pedido" />
        </div>
        <div class="text-center">
          <span>Fecha</span>
          <input class="form-control" type="date" v-model="sPedido.nueva_fecha" />
        </div>
        <div class="text-center">
          <button class="btn btn-primary" @click="registrarEnPedido">Aceptar</button>
        </div>
      </div>
    </MuroDeCarga>


    <InputBox v-if="bShowInputBox" :Titulo="sColumna" :Default="valoramostrar" @Listo="Resultado"></InputBox>


    <nav>
      <div class="nav nav-tabs">

        <a :class="'nav-item nav-link' + ((filtroEstado == item) ? ' active' : '')" v-for="(item, index) in [
          'AGREGAR',
          'LISTA',
          'CARGADO',
          'DISPONIBLE',
          'ELIMINADA'
        ]" :key="index" @click="filtroEstado = item;ListarSolicitudes();"> {{ item }} </a>

      </div>
    </nav>

    <div class="tab-content" >
      
    

    <table class="table tab-pane fade show active">
      <thead>
        <tr class="ajustar-ancho">
          <th scope="col-1">
            Legajo
            <div @click="Filtro('Legajo')">
              {{ filtro_legajo == "" ? "#######" : filtro_legajo }}
            </div>
          </th>
          <th scope="col-1">
            Nombre y Apellido
            <div @click="Filtro('Nombre')">
              {{ filtro_nombre == "" ? "#######" : filtro_nombre }}
            </div>
          </th>
          <th scope="col-1">
            Código
            <div @click="Filtro('Codigo')">
              {{ filtro_codigo == "" ? "#######" : filtro_codigo }}
            </div>
          </th>
          <th scope="col-9" style="width: 44%">
            Descripción
            <div @click="Filtro('Descripcion')">
              {{ filtro_descripcion == "" ? "#######" : filtro_descripcion }}
            </div>
          </th>
          <th scope="col">Fecha</th>
          <th scope="col">Comandos</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(a, key) of lista_solicitudes" :key="key" :class="esta == key ? 'selected' : ''"
          @click="SeleccionarItem($event, key, a)">
          <th scope="row">{{ a.Legajo }}</th>
          <td>{{ a.full_name }}</td>
          <td>{{ a.cod_herramienta }}</td>
          <td>{{ a.Descripcion }}</td>
          
          <td>
            {{ a.fecha_solicitud }}
          </td>
          <td>
            <div class="a-icon-group">
              <div class="a-icon flecha" @click="ActualizarSolicitud(a.id, 'PEDIDO')" title="Cargar en pedido">&#9654;
              </div>
              <div class="a-icon aplicar" title="Marcar como disponible" @click="ActualizarSolicitud(a.id, 'DISPONIBLE')">
              </div>
              <div class="a-icon editar" title="Editar" @click="ActualizarSolicitud(a.id)"></div>
              <div class="a-icon eliminar" title="Eliminar solicitud" @click="EliminarSolicitud(a.id)"></div>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

    <button class="btn bt-outline-primary" @click="AnteriorNivel" :disabled="nivel < 2">
      Anterior
    </button>
    <button class="btn bt-outline-primary" @click="SiguienteNivel" :disabled="lista_solicitudes.length < 6">
      Siguiente
    </button>
  </div>
</template>

<script>
import InputBox from "../components/InputBox.vue";
import MuroDeCarga from "../components/MuroDeCarga.vue";
import SolicitudTab from "../components/SolicitudTab.vue";

export default {
  name: "CargarOperarios",
  components: { InputBox, MuroDeCarga, SolicitudTab },
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
      editar_solicitud:{id_solicitud:0, nuevo_estado:"AGREGAR",nro_solicitud:"", nueva_fecha:""},
      esta: null,
      oItemSeleccionado: null,
      bMostrarEliminados: true,
      filtroEstado: "",
      TabSelect:"",
      sPedido: { id_sol: 0, bMostrar: false, nro_pedido: 0, nueva_fecha: "" }
    };
  },
  methods: {
    SeleccionarItem: function (event, key, objeto) {
      console.log(objeto);
      this.esta = key;
      this.oItemSeleccionado = objeto;
    },
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
        case "Estado":
          this.filtroEstado = valor;
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
        case "Estado":
          this.valoramostrar = this.filtroEstado;
          break;
      }

      this.bShowInputBox = true;
    },

    registrarEnPedido: function () {
      let that = this;

      var formData = new FormData();
      formData.append("id_sol", that.sPedido.id_sol);
      formData.append("estado", "CARGADO");
      formData.append("nro_sc", that.sPedido.nro_pedido);
      formData.append("fecha", that.sPedido.nueva_fecha);
      //console.log(this.sPedido);
      //return;
      // request options
      const options = {
        method: "POST",
        body: formData,
      };

      // send POST request
      fetch("/api/ActualizarSolicitud", options)
        .then((res) => res.text())
        .then((res) => that.DatosSolicitudRecibidos(res));
    },

    enviarClick: function () {
      let that = this;
      
      console.log(that.editar_solicitud.nuevo_estado);

      var formData = new FormData();
      formData.append("id_sol", that.editar_solicitud.id_solicitud);
      formData.append("estado", that.editar_solicitud.nuevo_estado);
      formData.append("nro_solicitud", that.editar_solicitud.nro_solicitud);
      formData.append("fecha", that.editar_solicitud.nueva_fecha);

      // request options
      const options = {
        method: "POST",
        body: formData,
      };

      // send POST request
      fetch("/api/ActualizarSolicitud", options)
        .then((res) => res.text())
        .then((res) => that.DatosSolicitudRecibidos(res));
    },

    DatosSolicitudRecibidos: function (datos) {
      this.bIngresoDatos = false;
      this.sPedido.bMostrar = false;
      this.editar_solicitud.nuevo_estado = "";
      this.editar_solicitud.id_solicitud = -1;
      this.editar_solicitud.nro_solicitud = "";
      console.log(datos);
      this.ListarSolicitudes();
    },

    ActualizarSolicitud: function (id_sol, estado) {

      if (estado == "PEDIDO") {
        this.sPedido.id_sol = id_sol;
        this.sPedido.bMostrar = true;

        return;
      }

      this.editar_solicitud.id_solicitud = id_sol;
      this.bIngresoDatos = true;

      if (estado == "DISPONIBLE") {
        this.bIngresoDatos = false;
        this.editar_solicitud.nuevo_estado = "DISPONIBLE";
        this.editar_solicitud.nro_solicitud = id_sol;
        let v = new Date();
        let sFecha = v.getFullYear() + "-";
        sFecha += (v.getMonth() + 1 < 10 ? "0" : "") + (v.getMonth() + 1) + "-";
        sFecha += (v.getDate() < 10 ? "0" : "") + v.getDate();
        this.editar_solicitud.nueva_fecha = sFecha;
        this.enviarClick();
      } else {
        //La solicitud es para editar mediante GUI
        this.editar_solicitud.id_solicitud = id_sol;
        //this.enviarClick();
      }
    },

    AnteriorNivel: function () {
      this.nivel--;
      this.ListarSolicitudes();
    },
    SiguienteNivel: function () {
      this.nivel++;
      this.ListarSolicitudes();
    },
    EliminarSolicitud: function (id_sol) {
      let that = this;
      this.bIngresoDatos = true;
      this.id_solicitud = id_sol;
      console.log(id_sol);

      var formData = new FormData();
      formData.append("id_sol", that.id_solicitud);
      formData.append("fecha", that.nueva_fecha);
      // request options
      const options = {
        method: "POST",
        body: formData,
      };

      // send POST request
      fetch("/api/EliminarSolicitud", options)
        .then((res) => res.text())
        .then((res) => that.DatosSolicitudRecibidos(res));
    },

    ListarSolicitudes: function () {
      let that = this;
      console.log("/api/ListarSolicitudes?nivel=" + (this.nivel - 1));

      var formData = new FormData();

      formData.append("legajo", that.filtro_legajo);
      formData.append("codigo", that.filtro_codigo);
      formData.append("nombre", that.filtro_nombre);
      formData.append("descripcion", that.filtro_descripcion);
      formData.append("bMostrarEliminados", that.bMostrarEliminados);
      formData.append("filtroEstado", that.filtroEstado);


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
      fetch("/api/InsertarOperario", options)
        .then((res) => res.text())
        .then((res) => that.DatosRecibidos(res));
    },
  },
  mounted() {
    this.filtroEstado = 'AGREGAR';
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

.table {
  font-size: small;
}

.ajustar-ancho>th:nth-child(1) {
  width: 117px;
}

.ajustar-ancho>th:nth-child(2) {
  width: 121px;
}

.ajustar-ancho>th:nth-child(3) {
  width: 160px;
}

.ajustar-ancho>th:nth-child(4) {
  width: 475px;
}

.ajustar-ancho>th:nth-child(5) {
  width: 120px;
}

.ajustar-ancho>th:nth-child(6) {
  width: 120px;
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

tr.selected {
  border: 2px solid black;
}

.a-icon-group {
  display: flex;
}

.a-icon {
  width: 32px;
  height: 32px;

  margin: 2px;
  border-color: black;
  border-width: 1px;
  border-radius: 2px;
  border-style: solid;
  background-position: center;
  background-repeat: no-repeat;
  cursor: pointer;
}

.a-icon.aplicar {
  background-image: url("./../assets/aplicar.png");
}

.a-icon.editar {
  background-image: url("./../assets/edit.png");
}

.a-icon.eliminar {
  background-image: url("./../assets/eliminar.gif");
}

.a-icon.seleccionar {
  background-image: url("./../assets/seleccionar.png");
}

.a-icon.flecha {
  width: 32px;
  height: 32px;
  content: "&#9654;";
  color: tomato;
  /**← ↑ → ↓ ▲ ▼ ◀ ▶ */
}
</style>