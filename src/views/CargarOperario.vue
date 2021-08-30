<template>
  <div class="container">
      <form>
        <div><label>Legajo:</label><input name="legajo" value="" /></div>
        <div><label>Apellido:</label><input name="apellido" value="" /></div>
        <div><label>Nombre:</label><input name="nombre" value="" /></div>
      </form> 
      <button :click="Get()">Click</button>
  </div>
</template>

<script>
export default {
  data() {
    return {
      
    }
  },
  methods: {
    Get: function(){
      console.log("Text");
      fetch('/api/index.php').then(response => response.text()).then(txt => alert(txt));
  
    }
  },
}
</script>