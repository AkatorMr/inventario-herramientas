<template>
  <div class="container">
    
    <ul class="list-group list-group-flush">
        <li class="list-group-item" v-for="(a,key) of lista_sectores" :key="key">{{a.Sector}}</li>
      </ul>
  </div>
</template>

<script>


export default {
  name: 'viewSectores',
  components: {
    
  },data() {
    return {
      lista_sectores:[]
    }
  },
  
  methods: {
    ListarSectores: function(){
      let that = this;
      fetch('/api/index.php?ListarSectores').then(response => response.json()).then(resp=>that.lista_sectores=resp);
  
    },
    
  },
  mounted() {
    this.ListarSectores();
  },
}
</script>
