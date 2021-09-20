<template>
    <div class="container my-5">
        <h2>{{establecimiento.nombre}}</h2>

        <div class="row align-items-start">
            <div class="col-md-8 order-2">
                <img :src='`../storage/${establecimiento.imagen_principal}`' 
                alt="imagen establecimiento" class="img-fluid">
                <p class="mt-5">{{establecimiento.descripcion}}</p>
                <galeria-imagenes></galeria-imagenes>
            </div>

            <aside class="col-md-4 order-1">
                <div>
                    <mapa-ubicacion></mapa-ubicacion>
                </div>

                <div class="p-4 bg-primary">
                    <h2 class="text-center text-white mt-2 mb-4">Más Información</h2>

                    <p class="text-white mt-1">
                        <span>Ubicación:</span>
                        {{establecimiento.direccion}}
                    </p>

                     <p class="text-white mt-1">
                        <span>Urbanización:</span>
                        {{establecimiento.urbanizacion}}
                    </p>

                     <p class="text-white mt-1">
                        <span>Horario:</span>
                        {{establecimiento.apertura}} - {{establecimiento.cierre}}
                    </p>

                      <p class="text-white mt-1">
                        <span>Teléfono:</span>
                        {{establecimiento.telefono}}
                    </p>



                </div>
            </aside>
        </div>
    </div>
</template>

<script>
import MapaUbicacion from './MapaUbicacion';
import GaleriaImagenes from './GaleriaImagenes.vue';
export default {

        components:{
            MapaUbicacion,
            GaleriaImagenes
                
        },

        mounted()
        {
            const { id } = this.$route.params;

            axios.get('/api/establecimientos/' + id)
                  .then(respuesta =>{
                      this.$store.commit("AGREGAR_ESTABLECIMIENTO", respuesta.data);
                  })
        },
        computed:
        {
            establecimiento(){
                return this.$store.getters.obtenerEstablecimiento;
            }
        }
}
</script>