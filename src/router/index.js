import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'

import AgregarOperario from '../views/CargarOperario.vue'
import AgregarSector from "../views/CargarSector.vue"
import AgregarHerramienta from "../views/CargarHerramientas.vue"

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/addope',
    name: 'Add-Operario',
    component: AgregarOperario
  },
  {
    path: '/addsec',
    name: 'Add-Sector',
    component: AgregarSector
  },
  {
    path: '/addher',
    name: 'Add-Herramienta',
    component: AgregarHerramienta
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
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
