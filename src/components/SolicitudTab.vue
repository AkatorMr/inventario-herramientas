<template>
    <div :class="'tab-pane fade ' + (Active ? 'show active' : '')" >
        
        <table class="table">
      <thead>
        <tr class="ajustar-ancho">
          <th scope="col">
            Legajo
            <div @click="Filtro('Legajo')">
              {{ filtro_legajo == "" ? "#######" : filtro_legajo }}
            </div>
          </th>
          <th scope="col">
            Nombre y Apellido
            <div @click="Filtro('Nombre')">
              {{ filtro_nombre == "" ? "#######" : filtro_nombre }}
            </div>
          </th>
          <th scope="col">
            Código
            <div @click="Filtro('Codigo')">
              {{ filtro_codigo == "" ? "#######" : filtro_codigo }}
            </div>
          </th>
          <th scope="col" style="width: 44%">
            Descripción
            <div @click="Filtro('Descripcion')">
              {{ filtro_descripcion == "" ? "#######" : filtro_descripcion }}
            </div>
          </th>
          <th scope="col">

            <div>Mostrar Eliminados: <input type="checkbox" v-model="bMostrarEliminados" @change="ListarSolicitudes()" />
            </div>
            <div>Estado</div>
            <div @click="Filtro('Estado')">
              {{ filtroEstado == "" ? "#######" : filtroEstado }}
            </div>
          </th>
          <th scope="col">Fecha</th>
          <th scope="col">Comandos</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(a, key) of lista_solicitudes" :key="key" :class="esta == key ? 'selected' : ''"
          @click="SeleccionarItem($event, key, a)">
          <th scope="row">{{ a.Legajo }}</th>
          <td>{{ a.full_name }}</td>
          <td>{{ a.cod_herramienta }}</td>
          <td>{{ a.Descripcion }}</td>
          <td>

            <!-- i v-if="a.estado=='DISPONIBLE'" class="fa-solid fa-check-double"></i>
            <i v-if="a.estado=='LISTA'" class="fa-solid fa-barcode-read"></i>
            <i v-if="a.estado=='AGREGAR'" class="fa-solid fa-plus"></i>
            <i v-if="a.estado=='CARGADO'" class="fa-solid fa-books-medical"></i> -->
            {{ a.estado }}

          </td>
          <td>
            {{ a.fecha_solicitud }}
          </td>
          <td>
            <div class="a-icon-group">
              <div class="a-icon flecha" @click="ActualizarSolicitud(a.id, 'PEDIDO')" title="Cargar en pedido">&#9654;
              </div>
              <div class="a-icon aplicar" title="Marcar como disponible" @click="ActualizarSolicitud(a.id, 'DISPONIBLE')">
              </div>
              <div class="a-icon editar" title="Editar" @click="ActualizarSolicitud(a.id)"></div>
              <div class="a-icon eliminar" title="Eliminar solicitud" @click="EliminarSolicitud(a.id)"></div>
            </div>
          </td>
        </tr>
      </tbody>
    </table>

   
        
    </div>
</template>

<script>

export default {
    components: {},
    props: {
        Active: Boolean,
        Estado: String,
        Nivel:Number
    },
    data() {
        return {
            valorentrada: "",
        };
    },
    methods: {
        sendClick: function () {

            this.$emit("Listo", valor);
        },

        
    },
    mounted() {

    },
};
</script>