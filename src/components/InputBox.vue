<template>
  <div class="MuroDeBloqueo">
    
      <div class="Recuadro">
        <div class="text-center"> {{Titulo}}</div>
        <div class="text-center"> 
          
          <input class="form-control" v-model="valorentrada">
        </div>
        <div class="text-center"> 
          
          <button class="btn btn-primary" @click="sendClick">Aceptar</button>
        </div>
      </div>
  </div>
</template>

<script>
export default {
  name: 'ListaOperarios',
  props: {
    Titulo: String,
    
  },
  data() {
    return {
      lista_operarios:[],
      valorentrada:""
    }
  },
  methods: {
    sendClick:function(){
      let valor = this.valorentrada;
      this.$emit("Listo",valor);
    },

    ListarOperarios: function(){
      let that = this;
      fetch('/api/index.php?ListarOperarios').then(response => response.json()).then(resp=>that.lista_operarios=resp);
  
    },
    
  },
  mounted() {
    this.ListarOperarios();
  },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.MuroDeBloqueo {
  position: fixed;
  left: 0;
  width: 100%;
  height: 100%;
  top: 0;
  /* background-color: rgba(58, 128, 0, 0.527); */
  z-index: 99990;
}

.Recuadro {
  
  margin-left: auto;
  margin-right: auto;
  margin-top:10%;
  margin-bottom: auto;
  width: 250px;
  height: 110px;
  border-radius: 5px;
  background-color: rgba(0, 204, 255);
  z-index: 99991;
  border: solid 1px black;
}
.Recuadro .form-control{
  width: 90% !important;
  margin: auto;
  margin-bottom: 5px;
}


</style>
