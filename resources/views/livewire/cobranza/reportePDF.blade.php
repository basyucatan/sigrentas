@php
    $filaNum = 0;
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ public_path('css/reportes.css') }}">
</head>
<body>
    <header class="cabecera">
        <img src="{{ public_path('img/logo.png') }}" class="logo">
        <div style="margin-left: 50px;">
            <strong style="font-size: 12pt;">REPORTE DE COBRANZA</strong><br>
            Generado el: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}<br>
        </div>
    </header>
    <div style="margin-bottom: 20px; page-break-inside: avoid;">
        <div style="background: #f8f9fa; padding: 8px 10px; border: 1px solid #dee2e6; margin-bottom: 8px;">
            <strong style="font-size: 10pt; text-transform: uppercase; color: #333;">PERIODO: DEL {{ $fechaIni }} AL {{ $fechaFin }}</strong>
        </div>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th width="15%">Fecha</th>
                    <th width="35%">Inquilino / Inmueble</th>
                    <th width="30%">Concepto</th>
                    <th width="20%" class="derecha">Monto Pagado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pagos as $pago)
                    @php $filaNum++; @endphp
                    <tr class="{{ $filaNum % 2 != 0 ? 'gris' : '' }}">
                        <td>{{ \Carbon\Carbon::parse($pago->fecha)->format('d/m/Y') }}</td>
                        <td>
                            <span class="negrita">{{ $pago->recibo?->contrato?->inquilino?->inquilino ?? 'N/A' }}</span><br>
                            <small style="color: #6c757d;">
                                {{ $pago->recibo?->contrato?->cuarto?->casa?->casa ?? '' }} - {{ $pago->recibo?->contrato?->cuarto?->cuarto ?? '' }}
                            </small>
                        </td>
                        <td>{{ $pago->recibo?->adicionales['concepto'] ?? 'Recibo de Renta' }}</td>
                        <td class="derecha negrita" style="color: #198754;">${{ number_format($pago->montoPago, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 15px;">No se registraron cobros en el rango de fechas seleccionado.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="derecha negrita">TOTAL COBRADO:</td>
                    <td class="derecha negrita" style="background: #e9ecef; color: #198754; font-size: 10pt;">
                        ${{ number_format($totalCobrado, 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div style="position: fixed; bottom: 0; width: 100%; font-size: 7pt;" class="derecha">
        Página <span class="pagina"></span>
    </div>
</body>
</html>