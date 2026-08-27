<script>
    (function() {
        let mapaOcupacion = null;
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
            green: crearIcono('green'),
            orange: crearIcono('orange'),
            red: crearIcono('red')
        };

        function initMapa() {
            const el = document.getElementById('IdMapa');
            if (!el) return;

            if (mapaOcupacion) {
                mapaOcupacion.remove();
                mapaOcupacion = null;
            }

            const rawData = el.getAttribute('data-casas');
            if (!rawData) return;

            const casas = JSON.parse(rawData);
            mapaOcupacion = L.map('IdMapa').setView([20.9673, -89.6237], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(mapaOcupacion);

            let bounds = [];

            casas.forEach(casa => {
                if (!casa.lat || !casa.lng || (casa.lat === 0 && casa.lng === 0)) return;

                bounds.push([casa.lat, casa.lng]);

                let color = 'green';
                if (casa.ocupados > 0 && casa.disponibles > 0) color = 'orange';
                else if (casa.disponibles === 0 && casa.totalCuartos > 0) color = 'red';

                let html = `
                    <div style="font-family: sans-serif; min-width: 200px;">
                        <strong style="font-size: 10pt; color: #0d6efd;">${casa.casa}</strong><br>
                        <small style="color: #6c757d;">Cuartos: ${casa.totalCuartos} | Ocupados: ${casa.ocupados} | Libres: ${casa.disponibles}</small>
                        <hr style="margin: 5px 0;">
                        <table style="width: 100%; font-size: 8pt; border-collapse: collapse;">
                            <thead>
                                <tr style="background: #f8f9fa;">
                                    <th style="text-align: left; padding: 2px;">Cuarto</th>
                                    <th style="text-align: left; padding: 2px;">Estatus</th>
                                    <th style="text-align: left; padding: 2px;">Inquilino</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                casa.cuartos.forEach(c => {
                    let colorEstatus = c.estatus === 'Ocupado' ? '#dc3545' : '#198754';
                    html += `
                        <tr>
                            <td style="padding: 2px;"><b>${c.cuarto}</b></td>
                            <td style="color: ${colorEstatus}; font-weight: bold; padding: 2px;">${c.estatus}</td>
                            <td style="padding: 2px;">${c.inquilino}</td>
                        </tr>
                    `;
                });

                html += `</tbody></table></div>`;

                L.marker([casa.lat, casa.lng], { icon: iconos[color] })
                    .addTo(mapaOcupacion)
                    .bindPopup(html);
            });

            if (bounds.length > 0) {
                mapaOcupacion.fitBounds(bounds, { padding: [30, 30] });
            }

            setTimeout(() => {
                if (mapaOcupacion) mapaOcupacion.invalidateSize();
            }, 200);
        }

        // Ejecución inmediata al inyectarse la vista por AJAX
        initMapa();

        // Re-inicialización ante mutaciones del DOM de Livewire
        Livewire.hook('morph.updated', () => {
            if (document.getElementById('IdMapa') && !mapaOcupacion) {
                initMapa();
            }
        });
    })();
</script>