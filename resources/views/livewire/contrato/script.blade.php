<script>
document.addEventListener("DOMContentLoaded", () => {
    const $canvas = document.querySelector("#canvas");
    const $btnLimpiar = document.querySelector("#btnLimpiar");
    const $btnDescargar = document.querySelector("#btnDescargar");
    const contexto = $canvas.getContext("2d");
    const colorPincel = "black";
    const colorFondo = "white";
    const grosor = 2;
    let haComenzadoDibujo = false;
    let xActual = 0;
    let yActual = 0;
    let xAnterior = 0;
    let yAnterior = 0;
    const obtenerXReal = (clientX) => clientX - $canvas.getBoundingClientRect().left;
    const obtenerYReal = (clientY) => clientY - $canvas.getBoundingClientRect().top;
    const limpiarCanvas = () => {
        contexto.fillStyle = colorFondo;
        contexto.clearRect(0, 0, $canvas.width, $canvas.height);
    };
    $canvas.addEventListener("mousedown", evento => {
        xAnterior = xActual;
        yAnterior = yActual;
        xActual = obtenerXReal(evento.clientX);
        yActual = obtenerYReal(evento.clientY);
        contexto.beginPath();
        contexto.fillStyle = colorPincel;
        contexto.clearRect(xActual, yActual, grosor, grosor);
        contexto.closePath();
        haComenzadoDibujo = true;
    });
    $canvas.addEventListener("mousemove", (evento) => {
        if (!haComenzadoDibujo) return;
        xAnterior = xActual;
        yAnterior = yActual;
        xActual = obtenerXReal(evento.clientX);
        yActual = obtenerYReal(evento.clientY);
        contexto.beginPath();
        contexto.moveTo(xAnterior, yAnterior);
        contexto.lineTo(xActual, yActual);
        contexto.strokeStyle = colorPincel;
        contexto.lineWidth = grosor;
        contexto.stroke();
        contexto.closePath();
    });
    ["mouseup", "mouseout"].forEach(nombreDeEvento => {
        $canvas.addEventListener(nombreDeEvento, () => {
            haComenzadoDibujo = false;
        });
    });
    limpiarCanvas();
    $btnLimpiar.onclick = limpiarCanvas;
    $btnDescargar.onclick = () => {
        const enlace = document.createElement('a');
        enlace.download = "Firma.png";
        enlace.href = $canvas.toDataURL();
        enlace.click();
    };
    window.obtenerImagen = () => $canvas.toDataURL();
    if (window.opener) {
        const $firma = document.querySelector("#firma");
        if ($firma) $firma.src = window.opener.obtenerImagen();
        window.print();
    }
});

// Agrega esto al final de tu evento "DOMContentLoaded" en script.js
const $btnGenerarDocumento = document.querySelector("#btnGenerarDocumento");

$btnGenerarDocumento.onclick = () => {
    // 1. Abrimos la nueva ventana (la ruta debe existir en web.php)
    const ventanaNueva = window.open("/contrato-imprimir", "_blank");
    
    // 2. Aquí NO necesitas llamar a nada.
    // La "magia" ocurre cuando el archivo contrato.blade.php se carga.
    // Ese archivo, al abrirse, detectará automáticamente que tiene un "padre"
    // y llamará por su cuenta a window.opener.obtenerImagen().
};
</script>