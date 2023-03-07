<template>
  <MuroDeCarga :bMostrar="true">
    <div class="Recuadro">
      <div class="text-center">{{ Titulo }}</div>
      <div class="text-center">
        <input class="form-control" v-model="valorentrada" />
      </div>
      <div class="text-center">
        <button class="btn btn-primary" @click="sendClick">Aceptar</button>
      </div>
    </div>
  </MuroDeCarga>
</template>

<script>
import MuroDeCarga from "./MuroDeCarga.vue";

export default {
  components: { MuroDeCarga },
  props: {
    Titulo: String,
    Default: String,
  },
  data() {
    return {
      valorentrada: "",
    };
  },
  methods: {
    sendClick: function () {
      let valor = this.valorentrada;
      this.$emit("Listo", valor);
    },

    ListarOperarios: function () {
      let that = this;
      fetch("/api/ListarOperarios")
        .then((response) => response.json())
        .then((resp) => (that.lista_operarios = resp));
    },
  },
  mounted() {
    //this.ListarOperarios();
    this.valorentrada = this.Default;
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.Recuadro {
  margin-left: auto;
  margin-right: auto;
  margin-top: 10%;
  margin-bottom: auto;
  width: 250px;
  height: 110px;
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
</style>
