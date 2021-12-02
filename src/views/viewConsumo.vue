<template>
  <div class="container">
    <div class="MuroDeCarga" v-if="bMuroDeCarga"></div>
    <div>
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
        <button
          v-if="lista_codigos_filtro.length == 0"
          @click="AgregarHerramientaFaltante"
          :class="
            'btn btn-' +
            (AgregarDo == 'Agregar' ? 'outline-info' : 'success') +
            ' form-control col-md'
          "
        >
          {{ AgregarDo }}
        </button>
      </div>

      <div class="mb-3 d-grid gap-1 d-md-flex justify-content-md-end">
        <button class="btn btn-outline-success me-md-1">Agregar</button>
      </div>
    </div>
    <input-drop-down
      :prompt="'Legajo o Nombre'"
      :lista_completa="lista_operarios"
      ref="ref_operario"
      @onchange="
        (val) => {
          tNombre = val;
        }
      "
    ></input-drop-down>

    <div class="input-group mb-3">
      <span class="input-group-text col-md-2">Vale Oracle:</span>

      <input
        class="form-control col-md"
        placeholder="Oracle"
        v-model="tVale.Oracle"
        type="number"
      />
      <span class="input-group-text col-md-1">Vale MP:</span>

      <input
        class="form-control col-md"
        placeholder="OT"
        v-model="tVale.MPot"
        type="number"
      />
      <input
        class="form-control col-md"
        placeholder="Vale"
        v-model="tVale.MPvale"
        type="number"
      />
    </div>
    <button class="btn btn-outline-primary" @click="GenerarConsumo()">
      Generar Consumo
    </button>

    <ul class="list-group list-group-flush">
      <li class="list-group-item" v-for="(a, key) of lista_consumos" :key="key">
        {{ a.Sector }}
      </li>
    </ul>
  </div>
</template>

<script>
import InputDropDown from "../components/InputDropDown.vue";
export default {
  name: "Consumos",
  components: { InputDropDown },
  data() {
    return {
      lista_consumos: [],
      lista_codigos: [],
      lista_codigos_filtro: [],
      lista_operarios: [],
      tCodigo: "",
      tCantidad: "",
      tDescripcion: "",
      bFocusCodigoODescripcion: true,
      tNombre: "",
      bMuroDeCarga: false,
      tVale: { Oracle: null, MPot: null, MPvale: null },
      AgregarDo: "Agregar",
    };
  },

  methods: {
    AgregarHerramientaFaltante: function () {
      this.AgregarDo = "Agregado";
    },

    GenerarConsumo: function () {
      console.log(this.tCodigo, this.tDescripcion, this.tNombre);
      let legajo = this.tNombre.split("|")[0];
      legajo = legajo.substring(0, 6);

      this.bMuroDeCarga = true;
      let that = this;

      var formData = new FormData();
      formData.append("legajo", legajo);
      formData.append("codigo", that.tCodigo);
      formData.append("cantidad", that.tCantidad);
      formData.append("valeoracle", that.tVale.Oracle);
      formData.append("mpot", that.tVale.MPot);
      formData.append("mpvale", that.tVale.MPvale);
      console.log(formData);
      //formData.append("nombre", that.op_nombre);
      //formData.append("sector", that.op_sector);
      // request options
      const options = {
        method: "POST",
        body: formData,
      };

      // send POST request
      fetch("/api/index.php?GenerarConsumo", options)
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
      this.tVale.Oracle = null;
      this.tVale.MPvale = null;
      this.tVale.MPot = null;
      this.$refs.ref_operario.LimpiarDatos();
    },
    ListarConsumos: function () {
      let that = this;
      fetch("/api/index.php?ListarConsumos")
        .then((response) => response.text())
        .then((resp) =>
          //that.lista_operarios = resp
          console.log(resp)
        );
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
    ListarCódigos: function () {
      let that = this;
      fetch("/api/index.php?ListarCodigos")
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
    this.ListarConsumos();
    this.ListarCódigos();
    this.ListarOperarios();
  },
};
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