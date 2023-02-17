import Vue from 'vue'
import VueRouter from 'vue-router'
import viewConsumo from '../views/viewConsumo.vue'

import AgregarOperario from '../views/CargarOperario.vue'
import viewSectores from "../views/viewSector.vue"
import AgregarHerramienta from "../views/CargarHerramientas.vue"
import viewSolicitud from "../views/viewSolicitud.vue"
import viewListaSolicitud from "../views/viewListaSolicitud.vue"
import viewListaConsumo from "../views/viewListaConsumos.vue"
import viewPruebas from "../views/viewPruebas.vue"
import viewAnalizar from "../analizar/viewAnalizar.vue"
import viewEstadisticaConsumo from "../views/viewEstadisticaConsumos.vue"
import viewListaSolicitudPedidos from "../views/viewListaSolicitudPedido.vue"


Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Main',
    component: viewConsumo
  },
  {
    path: '/prueba',
    name: 'Generar s',
    component: viewPruebas
  },
  {
    path: '/consumos',
    name: 'Generar Consumo',
    component: viewConsumo
  },
  {
    path: '/consumos/lista',
    name: 'Listar Consumos',
    component: viewListaConsumo
  },
  {
    path: '/consumos/estadistica',
    name: 'Estadistica Consumos',
    component: viewEstadisticaConsumo
  },
  { path: '/solicitud', name: 'Solicitud', component: viewSolicitud },
  { path: '/solicitud/lista', name: 'ListaSolicitud', component: viewListaSolicitud },
  { path: '/solicitud/lista/pedidos', name: 'ListaPedidos', component: viewListaSolicitudPedidos },
  {
    path: '/addope',
    name: 'Add-Operario',
    component: AgregarOperario
  },
  {
    path: '/addsec',
    name: 'Add-Sector',
    component: viewSectores
  },
  {
    path: '/addher',
    name: 'Add-Herramienta',
    component: AgregarHerramienta
  },
  {
    path: '/analizar',
    name: 'Analizar',
    component: viewAnalizar
  },
  {
    path: '/about',
    name: 'About',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: function () {
      return import(/* webpackChunkName: "about" */ '../views/About.vue')
    }
  }
]

const router = new VueRouter({
  mode: 'hash',
  base: process.env.BASE_URL,
  routes
})

export default router
