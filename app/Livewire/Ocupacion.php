<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Casa;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class Ocupacion extends Component
{
    public $vista = 'tabla';
    public $fechaCorte;

    public function mount()
    {
        $this->fechaCorte = Carbon::now()->format('Y-m-d');
    }

    public function generarPdf()
    {
        $fechaCorteObj = Carbon::parse($this->fechaCorte);

        $casas = Casa::with(['cuartos.contratos' => function ($q) use ($fechaCorteObj) {
            $q->where('fechaIni', '<=', $fechaCorteObj->format('Y-m-d'))
              ->where(function ($sub) use ($fechaCorteObj) {
                  $sub->whereNull('fechaFin')
                      ->orWhere('fechaFin', '>=', $fechaCorteObj->format('Y-m-d'));
              })
              ->with('inquilino');
        }])->get();

        $pdf = Pdf::loadView('livewire.ocupacion.ocupacionPDF', [
            'casas' => $casas,
            'fechaCorte' => $fechaCorteObj->format('d/m/Y')
        ]);

        $pdf->setPaper('letter', 'portrait');

        return response()->streamDownload(
            fn() => print($pdf->output()),
            "reporte_ocupacion_" . date('Ymd_His') . ".pdf",
            ['Content-Type' => 'application/pdf']
        );
    }

    public function render()
    {
        $fechaCorteObj = Carbon::parse($this->fechaCorte);

        $casas = Casa::with(['cuartos.contratos' => function ($q) use ($fechaCorteObj) {
            $q->where('fechaIni', '<=', $fechaCorteObj->format('Y-m-d'))
              ->where(function ($sub) use ($fechaCorteObj) {
                  $sub->whereNull('fechaFin')
                      ->orWhere('fechaFin', '>=', $fechaCorteObj->format('Y-m-d'));
              })
              ->with('inquilino');
        }])->get();

        $casasMapa = [];

        if ($this->vista === 'mapa') {
            $casasMapa = $casas->map(function ($casa) {
                $coords = explode(',', $casa->ubicacion ?? '0,0');
                $lat = trim($coords[0] ?? 0);
                $lng = trim($coords[1] ?? 0);

                $totalCuartos = $casa->cuartos->count();
                $ocupados = 0;

                $detallesCuartos = $casa->cuartos->map(function ($cuarto) use (&$ocupados) {
                    $contrato = $cuarto->contratos->first();
                    $estatus = $contrato ? 'Ocupado' : 'Disponible';
                    if ($contrato) $ocupados++;

                    return [
                        'cuarto' => $cuarto->cuarto,
                        'estatus' => $estatus,
                        'inquilino' => $contrato?->inquilino?->inquilino ?? 'N/A',
                        'montoRenta' => $contrato?->montoRenta ?? 0
                    ];
                });

                return [
                    'id' => $casa->id,
                    'casa' => $casa->casa,
                    'lat' => (float)$lat,
                    'lng' => (float)$lng,
                    'totalCuartos' => $totalCuartos,
                    'ocupados' => $ocupados,
                    'disponibles' => $totalCuartos - $ocupados,
                    'cuartos' => $detallesCuartos
                ];
            });
        }

        return view('livewire.ocupacion.view', [
            'casas' => $casas,
            'casasMapa' => $casasMapa
        ]);
    }
}