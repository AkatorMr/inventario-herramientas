<template>
  <div class="container">

    <table class="table">
      <thead>
        <tr class="ajustar-ancho">
          <th scope="col">
            Pedido Nro
          </th>
          <th scope="col">
            Cantidad Items
          </th>

          <th scope="col">Comandos</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(a, key) of lstPedidos" :key="key" :class="esta == key ? 'selected' : ''"
          @click="SeleccionarItem($event, key, a)">

          <td scope="row">{{ a.pedido }}</td>
          <td>{{ a.cantidaditems }}</td>

          <td>
            <button class="btn btn-outline-info" @click="MostrarDetalle(a.pedido)">Detalle</button>
          </td>
        </tr>
      </tbody>
    </table>

    <div class="container" @if="Detalle.bMostrar">
      <table class="table">
        <thead>
          <tr class="ajustar-ancho">
            <th scope="col" style="width: 18%;">
              C&oacute;digo
            </th>
            <th scope="col">
              Decripci&oacute;n
            </th>

            <th scope="col" style="width: 18%;">Cantidad</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(a, key) of Detalle.lstItems" :key="key">

            <td scope="row">{{ a.codigo }}</td>
            <td>{{ a.descripcion }}</td>
            <td>{{ a.cantidad }}</td>

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

export default {
  name: "ListaDePedidos",
  components: {},
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
      esta: null,
      oItemSeleccionado: null,
      bMostrarEliminados: false,
      filtroEstado: "",
      sPedido: { id_sol: 0, bMostrar: false, nro_pedido: 0, nueva_fecha: "" },
      Detalle: { bMostrar: false, lstItems: [], nroPedido: 0 },
      lstPedidos: []
    };
  },
  methods: {
    ListarPedidos: function (json) {
      if (json != undefined) {
        this.lstPedidos = json;

        return;
      }
      fetch("/api/ListarPedidos")
        .then((res) => res.json())
        .then((res) => this.ListarPedidos(res));
    },
    MostrarDetalle: function (nro_pedido) {
      this.Detalle.bMostrar = false;
      this.Detalle.lstItems = [];
      this.Detalle.nroPedido = nro_pedido;

      this.ObtenerDetalle();
    },
    ObtenerDetalle(json) {
      if (json != undefined) {
        this.Detalle.lstItems = json;
        this.Detalle.bMostrar = true;
        return;
      }
      var formData = new FormData();
      formData.append("nroPedido", this.Detalle.nroPedido);
      //console.log(this.sPedido);
      //return;
      // request options
      const options = {
        method: "POST",
        body: formData,
      };

      // send POST request
      fetch("/api/MostrarDetalles", options)
        .then((res) => res.json())
        .then((res) => this.ObtenerDetalle(res));
    },

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
      fetch("/api/ActualizarSolicitud", options)
        .then((res) => res.text())
        .then((res) => that.DatosSolicitudRecibidos(res));
    },

    DatosSolicitudRecibidos: function (datos) {
      this.bIngresoDatos = false;
      this.sPedido.bMostrar = false;
      this.nuevo_estado = "";
      this.id_solicitud = -1;
      console.log(datos);
      this.ListarSolicitudes();
    },

    ActualizarSolicitud: function (id_sol, estado) {

      if (estado == "PEDIDO") {
        this.sPedido.id_sol = id_sol;
        this.sPedido.bMostrar = true;

        return;
      }

      this.id_solicitud = id_sol;
      this.bIngresoDatos = true;

      if (estado == "DISPONIBLE") {
        this.bIngresoDatos = false;
        this.nuevo_estado = "DISPONIBLE";
        this.nro_solicitud = id_sol;
        let v = new Date();
        let sFecha = v.getFullYear() + "-";
        sFecha += (v.getMonth() + 1 < 10 ? "0" : "") + (v.getMonth() + 1) + "-";
        sFecha += (v.getDate() < 10 ? "0" : "") + v.getDate();
        this.nueva_fecha = sFecha;
        this.enviarClick();
      } else {
        //La solicitud es para editar
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
      formData.append("filtroEstado", "CARGADO");


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
    this.ListarPedidos();
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