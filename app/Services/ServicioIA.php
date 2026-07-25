<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;
class ServicioIA
{
    public static function analizarComprobante($rutaAbsolutaImagen)
    {
        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey || !file_exists($rutaAbsolutaImagen)) return null;
        $mimeType = mime_content_type($rutaAbsolutaImagen);
        $imagenBase64 = base64_encode(file_get_contents($rutaAbsolutaImagen));
        $prompt = "Analiza la imagen adjunta. Contiene uno o varios comprobantes/notas de gasto (pueden ser impresos o manuscritos). "
                . "Extrae de cada comprobante detectable: "
                . "1. 'monto' (número decimal estricto sin símbolos de moneda). "
                . "2. 'fecha' (en formato YYYY-MM-DD; si solo ves el mes como texto, conviértelo a número). "
                . "3. 'concepto' (breve resumen del concepto o proveedor). "
                . "Responde EXCLUSIVAMENTE con un JSON válido usando esta estructura exacta: "
                . '{"notas": [{"monto": 0.00, "fecha": "YYYY-MM-DD", "concepto": "string"}]}';
        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt],
                        [
                            'inline_data' => [
                                'mime_type' => $mimeType,
                                'data' => $imagenBase64
                            ]
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'response_mime_type' => 'application/json',
                'temperature' => 0.1
            ]
        ];
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}";
        $response = Http::withHeaders(['Content-Type' => 'application/json'])->post($url, $payload);
        if ($response->successful()) {
            $resultado = $response->json();
            $textoJson = $resultado['candidates'][0]['content']['parts'][0]['text'] ?? null;
            if ($textoJson) {
                return json_decode($textoJson, true);
            }
        } else {
            dd($response->json());
        }
        return null;
    }
}