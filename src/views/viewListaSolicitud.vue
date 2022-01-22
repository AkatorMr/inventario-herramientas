<template>
  <div class="container">
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Legajo</th>
          <th scope="col">Nombre</th>
          <th scope="col">Apellido</th>
          <th scope="col">Sector</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(a, key) of lista_solicitudes" :key="key">
          <th scope="row">{{ a.legajo }}</th>
          <td>{{ a.Nombre }}</td>
          <td>{{ a.Apellido }}</td>
          <td>{{ a.Sector }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: "CargarOperarios",
  components: {
  },
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
    };
  },
  methods: {
    ListarSolicitudes: function () {
      let that = this;
      fetch("/api/index.php?ListarSolicitudes")
        .then((response) => response.json())
        .then((resp) => (that.lista_solicitudes = resp));
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
.muro {
  position: absolute;
  left: 0;
  width: 100%;
  height: 100%;
  top: 0;
  background-color: rgba(0, 102, 128, 0.527);
  z-index: 99999;
}
</style>