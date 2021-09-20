import { map } from 'jquery';
import { OpenStreetMapProvider } from 'leaflet-geosearch';
const provider = new OpenStreetMapProvider();
document.addEventListener('DOMContentLoaded', () => {

    if(document.querySelector('#mapa')){
        
        const lat = document.querySelector('#lat').value === '' ? -12.0606211 : document.querySelector('#lat').value;
        
        const lng = document.querySelector('#lng').value === '' ? -77.0437697 : document.querySelector('#lng').value;
    
        const mapa = L.map('mapa').setView([lat, lng], 16);

        // Eliminar pines previews
        let markers =  new L.FeatureGroup().addTo(mapa);
    
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(mapa);
    
        let marker;
    
        // agregar el pin
        marker = new L.marker([lat, lng], {
            draggable: true,
            autoPan: true,
        }).addTo(mapa);

        markers.addLayer(marker);

        //Geocode Service

        const geocodeService = L.esri.Geocoding.geocodeService({
            apikey:'AAPKeb37dfb78aec47339c1901539585caa8caj0WFkph2e8ULsmvEHSqz8v86c2vSNWm-ws6zmDBjjqrn3gXoeI3g9M31NvytXb'
        });

        // Buscador de direcciones
        const buscador = document.querySelector('#formbuscador');
        buscador.addEventListener('blur', buscarDireccion);

        //detectar movimiento del marker
       reubicarPin(marker);

        function reubicarPin(marker){
            marker.on('moveend', function(e){
                marker = e.target;
                const posicion = marker.getLatLng();
    
                //centrar automaticamente
                mapa.panTo( new L.LatLng( posicion.lat, posicion.lng));
    
                //Reverse Geocoding, cuando el usuario reubica el pin
                geocodeService.reverse().latlng(posicion, 16).run(function(error, resultado){
                    // console.log(error);
                    // console.log(resultado.address);
    
                    marker.bindPopup(resultado.address.LongLabel);
                    marker.openPopup();
    
                    //LLenar los campos 
                    llenarInputs(resultado);
                })
    
            });
        }

        function llenarInputs(resultado){
            document.querySelector('#direccion').value = resultado.address.Address || '';
            document.querySelector('#urbanizacion').value = resultado.address.Neighborhood || '';
            document.querySelector('#lat').value = resultado.latlng.lat || '';
            document.querySelector('#lng').value = resultado.latlng.lng || '';
        }

        function buscarDireccion(e){

            // console.log(provider);

            if(e.target.value.length > 3){
                provider.search({query: e.target.value + ' Lima PE'})
                        .then( resultado => { 
                            if(resultado){

                                //limpiar los pines previos
                                markers.clearLayers();

                                geocodeService.reverse().latlng(resultado[0].bounds[0], 16).run(function(error, resultado){
                                    //LLenar los campos 
                                     llenarInputs(resultado);

                                    //Centra el mapa
                                    mapa.setView(resultado.latlng);

                                    // agregar el pin
                                    marker = new L.marker(resultado.latlng, {
                                        draggable: true,
                                        autoPan: true,
                                    }).addTo(mapa);

                                    //asignar el contenedor de markers el nuevo pin

                                    markers.addLayer(marker);

                                    reubicarPin(marker);
                                })
                    
                            }
                        })
            }
        }

    }
 
});