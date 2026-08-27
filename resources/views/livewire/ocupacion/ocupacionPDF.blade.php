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
            <strong style="font-size: 12pt;">REPORTE GENERAL DE OCUPACIÓN</strong><br>
            Generado el: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}<br>
        </div>
    </header>

    <div style="margin-bottom: 20px;">
        <div style="background: #f8f9fa; padding: 8px 10px; border: 1px solid #dee2e6; margin-bottom: 12px;">
            <strong style="font-size: 10pt; text-transform: uppercase; color: #333;">ESTADO DE OCUPACIÓN AL: {{ $fechaCorte }}</strong>
        </div>

        @forelse($casas as $casa)
            <div style="page-break-inside: avoid; margin-bottom: 15px;">
                <div style="background: #e9ecef; padding: 6px 10px; border: 1px solid #ced4da; font-weight: bold; font-size: 10pt; color: #212529;">
                    CASA: {{ strtoupper($casa->casa) }}
                </div>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th width="20%">Cuarto</th>
                            <th width="20%">Estado</th>
                            <th width="40%">Inquilino Vigente</th>
                            <th width="20%" class="derecha">Renta Mensual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($casa->cuartos as $cuarto)
                            @php 
                                $filaNum++; 
                                $contratoActivo = $cuarto->contratos->first();
                            @endphp
                            <tr class="{{ $filaNum % 2 != 0 ? 'gris' : '' }}">
                                <td class="negrita">{{ $cuarto->cuarto }}</td>
                                <td>
                                    @if($contratoActivo)
                                        <span style="color: #dc3545; font-weight: bold;">OCUPADO</span>
                                    @else
                                        <span style="color: #198754; font-weight: bold;">DISPONIBLE</span>
                                    @endif
                                </td>
                                <td>
                                    @if($contratoActivo)
                                        <span class="negrita">{{ $contratoActivo->inquilino?->inquilino ?? 'N/A' }}</span><br>
                                        <small style="color: #6c757d;">
                                            Del: {{ \Carbon\Carbon::parse($contratoActivo->fechaIni)->format('d/m/Y') }} 
                                            {{ $contratoActivo->fechaFin ? 'al ' . \Carbon\Carbon::parse($contratoActivo->fechaFin)->format('d/m/Y') : '(Indefinido)' }}
                                        </small>
                                    @else
                                        <span style="color: #6c757d;">---</span>
                                    @endif
                                </td>
                                <td class="derecha negrita">
                                    {{ $contratoActivo ? '$' . number_format($contratoActivo->montoRenta, 2) : '---' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @empty
            <table style="width: 100%; border-collapse: collapse;">
                <tbody>
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 15px;">No hay información registrada.</td>
                    </tr>
                </tbody>
            </table>
        @endforelse
    </div>

    <div style="position: fixed; bottom: 0; width: 100%; font-size: 7pt;" class="derecha">
        Página <span class="pagina"></span>
    </div>
</body>
</html>