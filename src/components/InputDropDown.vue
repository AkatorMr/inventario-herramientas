<template>
  <div class="input-group mb-3">
    <span class="input-group-text col-md-2" v-if="prompt!=''">{{ prompt }}:</span>

    <ul
      :class="
        'dropdown-menu ' + (lista_filtrada.length > 0 && bFocus ? 'show' : '')
      "
      style="
        position: absolute;
        inset: 0px auto auto 0px;
        margin: 0px;
        transform: translate(185px, 40px);
      "
    >
      <li>
        <a
          class="dropdown-item"
          href="#"
          v-for="(item, key) in lista_filtrada"
          :key="key"
          @mousedown="
            tInputField = item;
ActualizarValores();
            Filtrar();
          "
          >{{ item }}</a
        >
      </li>
      <li v-if="lista_filtrada.length == 11">
        <a class="dropdown-item">......</a>
      </li>
    </ul>
    <input
      type="text"
      class="form-control"
      :placeholder="prompt"
      :aria-label="prompt"
      v-model="tInputField"
      @keyup="Filtrar()"
      @focus="bFocus = true"
      @blur="bFocus = false"
      @change="ActualizarValores()"
    />
    <slot></slot>
  </div>
</template>
<script>
export default {
  name: "input-dropdown",
  props: {
    prompt: String,
    lista_completa: [],
    
  },
  data() {
    return {
      tInputField: "",
      lista_filtrada: [],
      bFocus: false,
    };
  },
  methods: {
    LimpiarValores:function(){
      this.tInputField="";
      this.bFocus=false;
    },
    ActualizarValores: function () {
      this.$emit("onchange", this.tInputField);
    },

    Filtrar: function () {
      
      this.lista_filtrada = [];
      for (let item in this.lista_completa) {
        let cod = this.lista_completa[item];
        console.log(cod);
        if (cod == null) continue;
        cod = cod.toUpperCase();

        if (
          cod.includes(this.tInputField.toUpperCase()) ||
          this.tInputField == ""
        ) {
          this.lista_filtrada.push(this.lista_completa[item]);
          if (this.lista_filtrada.length > 10) break;
        }
      }
    },
  },
  mounted() {},
};
</script>