<?php
namespace App\Services;
use Illuminate\Support\Facades\Storage;
class FirmaService 
{
    public static function guardarFirma($id, $dataUrl, $carpeta = 'contratos')
    {
        $imagenBase64 = preg_replace('/^data:image\/\w+;base64,/', '', $dataUrl);
        $datosImagen = base64_decode($imagenBase64);
        $nombreArchivo = "{$carpeta}_{$id}.png";
        Storage::disk('public')->put("{$carpeta}/{$nombreArchivo}", $datosImagen);
        return $nombreArchivo;
    }
}