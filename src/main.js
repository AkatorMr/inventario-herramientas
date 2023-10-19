import Vue from 'vue'
import App from './App.vue'
import router from './router'

Vue.config.productionTip = false

Vue.mixin({
  methods: {
    callTitle(...arr) {
      console.log(arr);
      console.log(this);
      console.log(this.$refs);
      this.$refs.PopUpMsgs.Mostrar(arr);
    },
  },
});

new Vue({
  router,
  render: function (h) { return h(App) }
}).$mount('#app')
