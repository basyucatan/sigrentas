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
            <strong style="font-size: 12pt;">RELACIÓN DE PAGO DE NÓMINA</strong><br>
            Generado el: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}<br>
            Departamento de Recursos Humanos
        </div>
    </header>
    @foreach($semanasProcesadas as $semana)
        <div style="margin-bottom: 30px; page-break-inside: avoid;">
            <div style="background: #f8f9fa; padding: 8px 10px; border: 1px solid #dee2e6; margin-bottom: 8px;">
                <strong style="font-size: 10pt; text-transform: uppercase; color: #333;">{{ $semana['rango'] }}</strong>
            </div>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th width="40%">Nombre del Empleado</th>
                        <th width="20%" class="derecha">Sueldo Semanal</th>
                        <th width="20%" class="derecha">Descuentos</th>
                        <th width="20%" class="derecha">Monto a Pagar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($semana['usuarios'] as $userPago)
                        @php $filaNum++; @endphp
                        <tr class="{{ $filaNum % 2 != 0 ? 'gris' : '' }}">
                            <td><span class="negrita">{{ $userPago['nombre'] }}</span></td>
                            <td class="derecha">${{ number_format($userPago['sueldoSemanal'], 2) }}</td>
                            <td class="derecha" style="color: #dc3545;">
                                @if($userPago['descuentoTotal'] > 0)
                                    -${{ number_format($userPago['descuentoTotal'], 2) }}
                                @else
                                    $0.00
                                @endif
                            </td>
                            <td class="derecha negrita" style="color: #198754;">${{ number_format($userPago['sueldoNeto'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td class="derecha negrita">TOTALES SEMANA:</td>
                        <td class="derecha negrita" style="background: #f8f9fa;">
                            ${{ number_format($semana['totalNeto'] + $semana['totalDescuentos'], 2) }}
                        </td>
                        <td class="derecha negrita" style="background: #f8f9fa; color: #dc3545;">
                            -${{ number_format($semana['totalDescuentos'], 2) }}
                        </td>
                        <td class="derecha negrita" style="background: #e9ecef; color: #198754; font-size: 10pt;">
                            ${{ number_format($semana['totalNeto'], 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endforeach
    <div style="position: fixed; bottom: 0; width: 100%; font-size: 7pt;" class="derecha">
        Página <span class="pagina"></span>
    </div>
</body>
</html>