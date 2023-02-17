<template>
  <div class="MuroDeCarga" v-if="bMostrar" @click.self="click2">
    <slot></slot>
  </div>
</template>

<script>
export default {
  name: "MuroDeCarga",
  props: {
    bMostrar: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      bIngresoDatos: false,
    };
  },
  methods: {
    Mostrar() {},
    click2(obj){
      this.$emit("click",obj);
      this.$emit("dismis",obj);
    },
    click3(obj){
      this.$emit("dismis",obj)
    }
  },
};
</script>
    
<style scoped>
.MuroDeCarga {
  position: fixed;
  left: 0px;
  width: 100%;
  height: 100%;
  top: 0px;
  background-color: rgba(0, 102, 128, 0.527);
  z-index: 99999;
}
</style>