<template>
    <div>
        <listado-categoria></listado-categoria>
        <mapa-establecimientos></mapa-establecimientos>
        <categoria-cafe></categoria-cafe>
        <categoria-restaurante></categoria-restaurante>
        <categoria-hotel></categoria-hotel>
    </div>

</template>

<script>
import MapaEstablecimientos from './MapaEstablecimientos';
import CategoriaCafe from './CategoriaCafe';
import CategoriaRestaurante from './CategoriaRestaurante';
import CategoriaHotel from './CategoriaHotel';
import ListadoCategoria from './ListadoCategoria';

export default {

    components:{
        MapaEstablecimientos,
        CategoriaCafe,
        CategoriaRestaurante,
        CategoriaHotel,
        ListadoCategoria,
        
    }
}
</script>