<template>
  <div class="">
    <!-- <button @click="ListarOperarios">Click</button> -->

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
        <tr
          v-for="(a, key) of lista_operarios"
          :key="key"
          :class="esta == key ? 'selected' : ''"
          @click="Seleccionar($event, key, a)"
        >
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
  name: "ListaOperarios",
  props: {
    msg: String,
  },
  data() {
    return {
      lista_operarios: [],
      esta:-1,
      oItemSeleccionado:null
    };
  },
  methods: {
    Seleccionar(event, key, objeto) {
      //console.log(objeto);
      this.esta = key;
      this.oItemSeleccionado = objeto;
      this.SelectionUpdate(objeto);
    },
    SelectionUpdate(obj){
      this.$emit("SelectionUpdate",obj);
    },
    ListarOperarios: function () {
      let that = this;
      fetch("/api/ListarOperarios")
        .then((response) => response.json())
        .then((resp) => (that.lista_operarios = resp));
    },
  },
  mounted() {
    this.ListarOperarios();
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h3 {
  margin: 40px 0 0;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}

tr.selected {
  border: 2px solid black;
}
</style>
