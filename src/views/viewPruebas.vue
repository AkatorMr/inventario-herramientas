<template>
  <div class="container ">
    <div class="Menu">
      <div>Uno</div>
      <div>Dos</div>
      <div>Tres</div>
      <div>Cuatro</div>
    </div>
  </div>
</template>

<style scoped>
.back {
  background-color: teal;
}
.Menu {
  width: 400px;
  height: 300px;
  

  margin: auto;
  position: relative;
 padding: 4px;
}

.Menu div {
  background-color: rgb(238, 238, 238);
  
  border-width: 2px;
  border-style: solid;
  border-color: white ;
}
.Menu div:hover {
  border-color: rgb(115, 106, 240);
}
.Menu div:nth-child(1) {
  position: absolute;

  width: 200px;
  height: 200px;
  left: 0;
  top: 0;
}
.Menu div:nth-child(2) {
  width: 200px;
  height: 100px;
  position: absolute;
  left: 200px;
  top: 0px;
}
.Menu div:nth-child(3) {
  width: 200px;
  height: 100px;
  position: absolute;
  left: 00px;
  top: 200px;
}
.Menu div:nth-child(4) {
  width: 200px;
  height: 200px;
  position: absolute;
  left: 200px;
  top: 100px;
}
</style>
