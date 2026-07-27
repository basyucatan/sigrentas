<script>
    let mapa = null;
    let marcadorUsuario = null;
    let ubicacionActual = null;
    let watchId = null;
    let primeraVezCentrado = false;
    const escalaIcono = 0.5;

    function crearIcono(color) {
        return L.icon({
            iconUrl: `https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-${color}.png`,
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25 * escalaIcono, 41 * escalaIcono],
            iconAnchor: [12 * escalaIcono, 41 * escalaIcono],
            popupAnchor: [1 * escalaIcono, -34 * escalaIcono],
            shadowSize: [41 * escalaIcono, 41 * escalaIcono]
        });
    }

    const iconos = {
        blue: crearIcono('blue'),
        green: crearIcono('green'),
        orange: crearIcono('orange')
    };

    function actualizarMarcadorUsuario(lat, lng) {
        if (!mapa) return;

        mapa.invalidateSize();

        if (!primeraVezCentrado) {
            mapa.setView([lat, lng], 16);
            primeraVezCentrado = true;
        }

        if (marcadorUsuario) {
            marcadorUsuario.setLatLng([lat, lng]);
        } else {
            marcadorUsuario = L.marker([lat, lng], {
                icon: iconos.blue
            }).addTo(mapa);
        }
    }

    function inicializarMapa() {
        const contenedorMapa = document.getElementById('IdMapa');

        if (!contenedorMapa) return;

        if (mapa) {
            mapa.remove();
            mapa = null;
            marcadorUsuario = null;
        }

        const todasLasCasas = JSON.parse(contenedorMapa.getAttribute('data-casas') || '[]');

        mapa = L.map('IdMapa').setView([20.9673, -89.6237], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapa);

        todasLasCasas.forEach(function(casa) {
            if (!casa.ubicacion) return;

            const partes = casa.ubicacion.split(',');

            if (partes.length !== 2) return;

            const latCasa = parseFloat(partes[0].trim());
            const lngCasa = parseFloat(partes[1].trim());

            const color = parseInt(casa.esAsignada) === 1 ? 'green' : 'orange';

            L.marker([latCasa, lngCasa], {
                icon: iconos[color]
            }).addTo(mapa).bindPopup(casa.casa || ('Casa ID: ' + casa.id));
        });

        if (ubicacionActual) {
            actualizarMarcadorUsuario(
                ubicacionActual.lat,
                ubicacionActual.lng
            );
        }
    }

function obtenerUbicacionYRegistrar() {
    if (!ubicacionActual) {
        @this.setFlash('error', 'Aún se está obteniendo tu ubicación GPS. Por favor, intenta de nuevo en un segundo.');
        return;
    }

    @this.registrarAsistencia(
        {{ auth()->id() ?? 1 }},
        ubicacionActual.lat + ',' + ubicacionActual.lng
    );
}
function procesarImagenInput(input) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = function(e) {
            @this.setFotoBase64(e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}
    document.addEventListener('livewire:navigated', function() {

        primeraVezCentrado = false;

        inicializarMapa();

        if (!navigator.geolocation) {
            alert('Tu navegador no soporta geolocalización.');
            return;
        }

        if (watchId !== null) {
            navigator.geolocation.clearWatch(watchId);
        }

        watchId = navigator.geolocation.watchPosition(
            function(position) {

                ubicacionActual = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                    precision: position.coords.accuracy
                };

                console.log(
                    'Lat:',
                    ubicacionActual.lat,
                    'Lng:',
                    ubicacionActual.lng,
                    'Precisión:',
                    Math.round(ubicacionActual.precision) + ' m'
                );

                actualizarMarcadorUsuario(
                    ubicacionActual.lat,
                    ubicacionActual.lng
                );
            },
            function(error) {
                console.error(error);
            },
            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0
            }
        );
    });

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Livewire !== 'undefined') {
            inicializarMapa();
        }
    });

    Livewire.hook('morph.updated', ({ el }) => {
        if (document.getElementById('IdMapa') && !mapa) {
            inicializarMapa();
        } else if (mapa) {
            mapa.invalidateSize();
        }
    });
</script>