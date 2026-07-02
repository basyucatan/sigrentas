<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documento con firma</title>
    <style>
        img {
            display: block;
            margin-left: auto;
            margin-right: auto;
            max-width: 300px;
        }
    </style>
</head>
<body>
    <h1>Título del documento</h1>
    <strong>Simple documento para demostrar cómo se puede colocar una firma del usuario</strong>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Et magnam eius reprehenderit repudiandae...</p>
    <h2>A continuación la firma</h2>
    <img src="" alt="Firma del usuario" id="firma">
    <br>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            if (window.opener) {
                const $firma = document.querySelector("#firma");
                $firma.src = window.opener.obtenerImagen();
                $firma.onload = () => {
                    window.print();
                };
            }
        });
const $btnGenerarDocumento = document.querySelector("#btnGenerarDocumento");

$btnGenerarDocumento.onclick = () => {
    // Abrimos la ruta donde reside tu archivo 'contrato.blade.php'
    // Asegúrate de que esta URL sea la ruta real de tu aplicación Laravel
    const ventanaNueva = window.open("/ruta-a-tu-contrato", "_blank");
};
    </script>
</body>
</html>