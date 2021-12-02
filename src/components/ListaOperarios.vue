<template>
  <div class="">
    
      <button @click="ListarOperarios">Click</button>
      <ul class="list-group list-group-flush">
        <li class="list-group-item" v-for="(a,key) of lista_operarios" :key="key">{{a.Nombre}}</li>
      </ul>
  </div>
</template>

<script>
export default {
  name: 'ListaOperarios',
  props: {
    msg: String,
    
  },
  data() {
    return {
      lista_operarios:[]
    }
  },
  methods: {
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
