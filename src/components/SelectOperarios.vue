<template>
  <div class="container" style="position: relative; top: 50%; ">
    <div style="width: 300px;margin-right: auto;margin-left: auto;">
    <InputDropDown style=""
    :prompt="'New'"
      :lista_completa="lista_operarios_string"
      @onchange="AlCambiar"
    ></InputDropDown>
    </div>
  </div>
</template>

<script>
import InputDropDown from "./InputDropDown.vue";

export default {
  name: "ListaOperarios",
  components: { InputDropDown },
  props: {
    msg: String,
  },
  data() {
    return {
      lista_operarios: [],
      lista_operarios_string: [],
    };
  },
  methods: {
    AlCambiar: function (texto) {
      for (let i = 0; i < this.lista_operarios_string; i++) {
        if (texto == this.lista_operarios_string[i]) {
          this.$emit("OnSelect", this.lista_operarios[i]);
          return;
        }
      }
    },
    Simplificar: function () {
      let that = this;
      that.lista_operarios_string = [];
      this.lista_operarios.forEach((element) => {
        that.lista_operarios_string.push(
          element.Nombre + " " + element.Apellido
        );
      });
    },
    ListarOperarios: function () {
      let that = this;
      fetch("/api/index.php?ListarOperarios")
        .then((response) => response.json())
        .then((resp) => {
          console.log(resp);
          that.lista_operarios = resp;
          that.Simplificar();
        });
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
</style>
