<template>
  <div class="container">
    <div class="MuroDeCarga" v-if="bMuroDeCarga"></div>
    <div>
      <div v-for="(a,key) of lista_a_consumir" :key="key" class="input-group mb-3">
        <span class="input-group-text col-md-2">Código y Descripción:</span>
        <input
          type="text"
          class="form-control col-md-1"
          placeholder="Código"
          aria-label="Código"
          v-model="a.tCodigo"
          @keyup="FiltrarHerramienta(a)"
          @focus="a.bFocusCodigoODescripcion = true"
          @blur="a.bFocusCodigoODescripcion = false"
        />

        <input
          type="text"
          class="form-control col-md"
          style="width: 35%"
          placeholder="Descripción"
          aria-label="Descripción"
          v-model="a.tDescripcion"
          @keyup="FiltrarHerramienta(a)"
          @focus="a.bFocusCodigoODescripcion = true"
          @blur="a.bFocusCodigoODescripcion = false"
        />

        <ul
          :class="
            'dropdown-menu ' +
            (lista_codigos_filtro.length > 0 && a.bFocusCodigoODescripcion
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
                a.tCodigo = item.Codigo;
                a.tDescripcion = item.Descripcion;
                FiltrarHerramienta(a);
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
          v-model="a.tCantidad"
          type="number"
        />
        <button
          v-if="lista_codigos_filtro.length == 0 && (a.tCodigo !='' || a.tDescripcion!='')"
          @click="AgregarHerramientaFaltante(a)"
          :class="
            'btn btn-' +
            (AgregarDo == 'Agregar Código' ? 'outline-info' : 'success') +
            ' form-control col-md'
          "
        >
          {{ AgregarDo }}
        </button>
      </div>

      <div class="mb-3 d-grid gap-1 d-md-flex justify-content-md-end">
        <button class="btn btn-outline-success me-md-1" v-on:click="AgregarLineaConsumo">Agregar Línea</button>
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
    >
    <span class="input-group-text col-md-1">Fecha:</span>

      <input
        class="form-control col-md-1"
        placeholder="dd/mm/yyyy"
        v-model="tVale.Fecha"
        type="date"
      />
    </input-drop-down>

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

    
  </div>
</template>

<script>
import InputDropDown from "../components/InputDropDown.vue";
export default {
  name: "Consumos",
  components: { InputDropDown },
  data() {
    return {
      lista_codigos: [],
      lista_codigos_filtro: [],
      lista_operarios: [],
      tCodigo: "",
      tCantidad: "",
      tDescripcion: "",
      bFocusCodigoODescripcion: true,
      tNombre: "",
      bMuroDeCarga: false,
      tVale: { Oracle: null, MPot: null, MPvale: null, Fecha:null },
      AgregarDo: "Agregar",
      lista_a_consumir:[],
    };
  },

  methods: {
    AgregarHerramientaFaltante: function (a) {
      //this.AgregarDo = "Agregado";
      console.log(a);
      
      this.bMuroDeCarga = true;
      let that = this;
      let codigo = a.tCodigo;
      let descri = a.tDescripcion;

      var formData = new FormData();
      formData.append("codigo", codigo);
      formData.append("descripcion", descri);
      
      console.log(formData);
      const options = {
        method: "POST",
        body: formData,
      };

      // send POST request
      fetch("/api/AgregarCodigo", options)
        .then((res) => res.json())
        .then((res) => that.HandlerHerramientaFaltante(res,a));

    },

    LanzarNotificacion:function(text){
      console.log(text);
    },
    HandlerHerramientaFaltante: function(da,a){
      this.bMuroDeCarga = false;
      if(da == "ok"){
        this.LanzarNotificacion("El elemento " + a.tCodigo + " se a agregado!");
      }else if(da == "ac"){
        this.LanzarNotificacion("El elemento " + a.tCodigo + " se a actualizado!");
      }

    },

    GenerarConsumo: function () {
      console.log(this.tCodigo, this.tDescripcion, this.tNombre);
      let legajo = this.tNombre.split("|")[0];
      legajo = legajo.substring(0, 6);

      this.bMuroDeCarga = true;
      let that = this;

      var formData = new FormData();
      formData.append("legajo", legajo);

      for(let index in this.lista_a_consumir){
        formData.append("codigo["+index+"]", that.lista_a_consumir[index].tCodigo);
        formData.append("cantidad["+index+"]", that.lista_a_consumir[index].tCantidad);
      }
      formData.append("valeoracle", that.tVale.Oracle);
      formData.append("mpot", that.tVale.MPot);
      formData.append("mpvale", that.tVale.MPvale);
      formData.append("valefecha", that.tVale.Fecha);
      console.log(formData);
      //formData.append("nombre", that.op_nombre);
      //formData.append("sector", that.op_sector);
      // request options
      const options = {
        method: "POST",
        body: formData,
      };

      // send POST request
      fetch("/api/GenerarConsumo", options)
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
    AgregarLineaConsumo:function(){
      this.lista_a_consumir.push({
            tCodigo: "",
            tCantidad: "",
            tDescripcion: "",
            bFocusCodigoODescripcion: true
          });
    },
    LimpiarDatos: function () {
      this.tCodigo = "";
      this.tDescripcion = "";
      this.tNombre = "";
      this.tCantidad = 0;
      this.tVale.Oracle = null;
      this.tVale.MPvale = null;
      this.tVale.MPot = null;
      this.tVale.Fecha = null;
      this.lista_a_consumir = [];
      this.AgregarLineaConsumo();
      this.FiltrarHerramienta(this.lista_a_consumir[0]);
      this.bFocusCodigoODescripcion=false;
      this.$refs.ref_operario.LimpiarValores();
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
    FiltrarHerramienta: function (linea) {
      this.lista_codigos_filtro = [];
      for (let item in this.lista_codigos) {
        let cod = this.lista_codigos[item].Codigo;
        if (cod == null) continue;

        let desc = this.lista_codigos[item].Descripcion;
        if (desc == null) continue;

        if (
          (cod.includes(linea.tCodigo.toUpperCase()) || linea.tCodigo == "") &&
          (desc.includes(linea.tDescripcion.toUpperCase()) ||
            linea.tDescripcion == "")
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
    
    this.ListarCódigos();
    this.ListarOperarios();

    this.AgregarLineaConsumo();
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