<template>
  <div class="container">
    <div class="MuroDeCarga2" v-if="bIngresoDatos"></div>

    <div class="container">
      <ul class="nav justify-content-center embellecedor">
        <li class="nav-item">
          <a class="nav-link active" href="#">Dar de baja</a>
        </li>
        <li class="nav-item">
          <a class="nav-link"  @click="MostrarVentana=true;">Transferir</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Devolver</a>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" href="#">Disabled</a>
        </li>
      </ul>
    </div>
    <InputBox
      v-if="bShowInputBox"
      :Titulo="sColumna"
      :Default="valoramostrar"
      @Listo="Resultado"
    ></InputBox>
    <MuroDeCarga :bMostrar="MostrarVentana">
      
      <SelectOperarios @OnSelect="CambiarS"></SelectOperarios>
    </MuroDeCarga>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Nro</th>
          
          <th scope="col">
            <span>Código</span>
            <div @click="Filtro('Codigo')">
              {{ filtro_codigo == "" ? "#######" : filtro_codigo }}
            </div>
          </th>
          <th scope="col">
            <span>Descripción</span>
            <div @click="Filtro('Descripcion')">
              {{ filtro_descripcion == "" ? "#######" : filtro_descripcion }}
            </div>
          </th>
          <th scope="col">
            <span>Cantidad</span>
            <div @click="Filtro('Cantidad')">
              {{ filtro_cantidad == "" ? "#######" : filtro_cantidad }}
            </div>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(a, key) of lista_consumos"
          :key="key"
          :class="esta == key ? 'selected' : ''"
          @click="pasar($event, key, a)"
        >
          <th>{{ key + (nivel - 1) * 15 + 1 }}</th>
          <td>{{ a.Codigo }}</td>
          <td class="text-start">{{ a.Descripcion }}</td>
          <td>{{ a.Cantidad }}</td>
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
import MuroDeCarga from "../components/MuroDeCarga.vue";
import ListaOperarios from "../components/ListaOperarios.vue";
import SelectOperarios from "../components/SelectOperarios.vue";

export default {
  name: "CargarOperarios",
  components: { InputBox,MuroDeCarga ,ListaOperarios,SelectOperarios},
  data() {
    return {
      esta: -1,
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
      filtro_cantidad:0,
      filtro_encabezado:"",
      valoramostrar: "",
      oItemSeleccionado: Object,
      MostrarVentana:false
    };
  },
  methods: {
    CambiarS(t,a,b){
      console.log(t,a,b);
    },
    pasar(event, key, objeto) {
      console.log(objeto);
      this.esta = key;
      this.oItemSeleccionado = objeto;
    },
    TransferirHerramienta: function (nuevodueño) {
      if(this.oItemSeleccionado==null) return;
      if(this.esta == -1) return;

       var formData = new FormData();

      formData.append("legajo", this.oItemSeleccionado.Legajo);
      formData.append("codigo", this.oItemSeleccionado.Codigo);
      formData.append("legajo_nuevo", nuevodueño);
      
      const options = {
        method: "POST",
        body: formData,
      };
      let that = this;
      fetch("/api/index.php?TransferirHerramienta", options)
        .then((response) => response.json())
        .then((resp) => {
          //that.lista_operarios = resp
          that.MostrarVentana=false;
          //console.log(resp);
        });

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
      }
      
      this.ListarConsumos();
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

      fetch("/api/index.php?ListarConsumosEstadistica&nivel=" + (this.nivel - 1), options)
        .then((response) => response.json())
        .then((resp) => {
          //that.lista_operarios = resp
          that.lista_consumos = resp;
          console.log(resp);
        });

      this.esta = -1;
      this.oItemSeleccionado = null;
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

.MuroDeCarga2 {
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

tr.selected {
  border: 2px solid black;
}

.embellecedor {
  border: 1px solid black;
}
</style>