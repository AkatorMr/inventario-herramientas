<template>
    <div class="Position" :key="valor" >

        <template v-for="(item, key) in msgList">
            <div class="alert alert-success fade show" v-if="item.estado == 'ok'">
                {{ item.msg }}
            </div>
            <div class="alert alert-danger fade show" v-if="item.estado == 'error'">
                {{ item.msg }}
            </div>
            <div class="alert alert-warning fade show" v-if="item.estado == 'fallo'">
                {{ item.msg }}
            </div>
            <div class="alert alert-info fade show" v-if="item.estado == 'info'">
                {{ item.msg }}
            </div>
        </template>

        <!-- <button @click="$refs.myChild.sayHello()">Click me</button>
        <child-component ref="myChild" /> -->


    </div>
</template>
  
<script>
export default {
    name: "PopUpMsg",
    props: {
        
    },
    data() {
        return {
            msgList: [],
            valor:0

        };
    },
    methods: {
        Mostrar(mensaje,estado) {
            let that = this;
            setTimeout(() => {
                that.msgList.splice(0,1);
                console.log(that.msgList);
                that.valor++;
            }, 5000);
            this.msgList.push({msg:mensaje,estado:estado});
         },
    },
};
</script>
      
<style scoped>
.Position {
    position: fixed;
    right: 0px;
    /* width: 100%;
    height: 100%; */
    top: 0px;
    /* background-color: rgba(0, 102, 128, 0.527); */
    z-index: 99999;
}
</style>