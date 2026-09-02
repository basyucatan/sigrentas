@php
    $filaNum = 0;
    $mapaColores = [
        'bg-success' => '#198754',
        'bg-danger' => '#dc3545',
        'bg-warning text-dark' => '#ffc107',
        'bg-info text-dark' => '#0dcaf0'
    ];
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ public_path('css/reportes.css') }}">
    <style>
        .badge-pdf {
            display: inline-block;
            padding: 3px 7px;
            font-size: 8pt;
            font-weight: bold;
            color: #ffffff;
            border-radius: 3px;
            text-align: center;
            min-width: 55px;
        }
        .text-dark {
            color: #212529 !important;
        }
    </style>
</head>
<body>
    <header class="cabecera">
        <img src="{{ public_path('img/logo.png') }}" class="logo">
        <div style="margin-left: 50px;">
            <strong style="font-size: 12pt;">Reporte de Añejamiento</strong><br>
            {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}<br>
        </div>
    </header>
    <div style="margin-bottom: 20px;">
        <div style="background: #f8f9fa; padding: 8px 10px; border: 1px solid #dee2e6; margin-bottom: 12px;">
            <strong style="font-size: 10pt; text-transform: uppercase; color: #333;">Total de contratos {{ $totalContratos }}</strong>
        </div>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th width="6%">Cont.</th>
                    <th width="22%">Inquilino / Ubicación</th>
                    <th width="18%">Último Pago</th>
                    <th width="22%">Recibo Vencido</th>
                    <th width="16%" class="derecha">Añej. Deuda</th>
                    <th width="16%" class="derecha">Añej. Pago</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contratos as $contrato)
                    @php 
                        $filaNum++; 
                        $adeudo = $contrato->aniejaAdeudo;
                        $pago = $contrato->aniejaPago;
                        $labelAdeudo = trim(str_replace(['⏱️', '⏱'], '', $adeudo['label']));
                        $labelPago = trim(str_replace(['⏱️', '⏱'], '', $pago['label']));
                        $bgAdeudo = $mapaColores[$adeudo['color']] ?? '#6c757d';
                        $bgPago = $mapaColores[$pago['color']] ?? '#6c757d';
                        $nombreCasa = $contrato->cuarto?->casa?->casa ?? 'Sin Casa';
                        $nombreCuarto = $contrato->cuarto?->cuarto ?? 'N/A';
                    @endphp
                    <tr class="{{ $filaNum % 2 != 0 ? 'gris' : '' }}">
                        <td><span class="negrita">#{{ str_pad($contrato->id, 4, '0', STR_PAD_LEFT) }}</span></td>
                        <td>
                            <span class="negrita">{{ $contrato->inquilino?->inquilino ?? 'N/A' }}</span><br>
                            <small style="color: #6c757d;">{{ $nombreCasa }} - {{ $nombreCuarto }}</small>
                        </td>
                        <td>
                            <span class="negrita">{{ $pago['fecha'] }}</span>
                        </td>
                        <td>
                            @if($adeudo['fechaVence'])
                                <span class="negrita" style="color: #dc3545;">${{ number_format($adeudo['monto'], 2) }}</span><br>
                                <small style="color: #6c757d;">Vencimiento: {{ $adeudo['fechaVence'] }}</small>
                            @else
                                <span style="color: #198754;">Sin adeudos</span>
                            @endif
                        </td>
                        <td class="derecha">
                            <span class="badge-pdf {{ str_contains($adeudo['color'], 'text-dark') ? 'text-dark' : '' }}" style="background-color: {{ $bgAdeudo }};">
                                {{ $labelAdeudo }}
                            </span>
                        </td>
                        <td class="derecha">
                            <span class="badge-pdf {{ str_contains($pago['color'], 'text-dark') ? 'text-dark' : '' }}" style="background-color: {{ $bgPago }};">
                                {{ $labelPago }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 15px;">No se encontraron contratos vigentes.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="position: fixed; bottom: 0; width: 100%; font-size: 7pt;" class="derecha">
        Página <span class="pagina"></span>
    </div>
</body>
</html>