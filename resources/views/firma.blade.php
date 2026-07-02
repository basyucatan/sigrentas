<div class="modal-overlay" 
     x-data="{ show: @entangle('show') }" 
     x-show="show" 
     style="display: none !important; z-index: 100000 !important;" 
     x-cloak>
    <div class="modalDialog">
        <div class="modal-content">
            <div class="cardPrin">
                <div class="cardPrin-header" style="cursor: move;">
                    <span>Capturar Firma</span>
                </div>
                <div class="cardPrin-body" style="padding: 10px;">
                    <canvas id="canvasFirma" width="400" height="200" style="border:1px solid #ccc; background: white; width: 100%;"></canvas>
                </div>
                <div class="cardPrin-footer d-flex justify-content-end gap-2">
                    <button type="button" onclick="limpiar()" class="bot botNegro botChico">Limpiar</button>
                    <button type="button" @click="show = false" class="bot botRojo botChico">Cerrar</button>
                    <button type="button" onclick="guardar()" class="bot botVerde botChico">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function limpiar() {
            const canvas = document.getElementById('canvasFirma');
            canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
        }
        function guardar() {
            const canvas = document.getElementById('canvasFirma');
            @this.guardarFirma(canvas.toDataURL('image/png'));
        }
    </script>
</div>