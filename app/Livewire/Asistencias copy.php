<?php
namespace App\Livewire;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\{Asistencia, Util, User};
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
class Asistencias extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $verModalAsistencia = false;
    public $keyWord;
    public $justificacion;
    public $selectedId;
    public $penaEntradaId;
    public $penaSalidaId;
    public $justificacionEntrada;
    public $justificacionSalida;
    public $todasLasCasas = [];
    public function updatedKeyWord()
    {
        $this->resetPage();
    }
    #[Computed]
    public function filteredAsistencias()
    {
        $keyWord = '%' . $this->keyWord . '%';
        return Asistencia::where('IdUser', Auth::User()->id)
            ->where(function ($query) use ($keyWord) {
                $query->orWhere('IdUser', 'LIKE', $keyWord)
                    ->orWhere('fecha', 'LIKE', $keyWord)
                    ->orWhere('horaEnt', 'LIKE', $keyWord)
                    ->orWhere('horaSal', 'LIKE', $keyWord)
                    ->orWhere('ubiEnt', 'LIKE', $keyWord)
                    ->orWhere('ubiSal', 'LIKE', $keyWord);
            })
            ->orderBy('fecha', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(12);
    }
    public function render()
    {
        $userId = auth()->id() ?? 0;
        $this->todasLasCasas = DB::table('casas')
            ->leftJoin('asignacions', function($join) use ($userId) {
                $join->on('casas.id', '=', 'asignacions.IdCasa')
                     ->where('asignacions.IdUser', '=', $userId);
            })
            ->select('casas.id', 'casas.ubicacion', 'casas.casa', DB::raw('IF(asignacions.id IS NOT NULL, 1, 0) as esAsignada'))
            ->get()
            ->toArray();
        return view('livewire.asistencias.view', [
            'asistencias' => $this->filteredAsistencias,
        ]);
    }
    public function iniciarEdicion($id)
    {
        $asistencia = Asistencia::findOrFail($id);
        $this->selectedId = $asistencia->id;
        $adicionales = $asistencia->adicionales;
        if (is_string($adicionales)) {
            $adicionales = json_decode($adicionales, true) ?? [];
        }
        $this->penaEntradaId = $adicionales['penaEntradaId'] ?? '';
        $this->penaSalidaId = $adicionales['penaSalidaId'] ?? '';
        $this->justificacionEntrada = $adicionales['justificacionEntrada'] ?? '';
        $this->justificacionSalida = $adicionales['justificacionSalida'] ?? '';
        $this->verModalAsistencia = true;
    }
    public function cancelarEdicion()
    {
        $this->selectedId = null;
        $this->penaEntradaId = '';
        $this->penaSalidaId = '';
        $this->justificacionEntrada = '';
        $this->justificacionSalida = '';
        $this->verModalAsistencia = false;
    }
    public function guardarEdicion()
    {
        if ($this->selectedId) {
            $asistencia = Asistencia::findOrFail($this->selectedId);
            $adicionales = $asistencia->adicionales;
            if (is_string($adicionales)) {
                $adicionales = json_decode($adicionales, true) ?? [];
            }
            if (!empty($this->penaEntradaId)) {
                $adicionales['penaEntradaId'] = intval($this->penaEntradaId);
            } else {
                unset($adicionales['penaEntradaId']);
            }
            if (!empty($this->penaSalidaId)) {
                $adicionales['penaSalidaId'] = intval($this->penaSalidaId);
            } else {
                unset($adicionales['penaSalidaId']);
            }
            if (!empty($this->justificacionEntrada)) {
                $adicionales['justificacionEntrada'] = $this->justificacionEntrada;
            } else {
                unset($adicionales['justificacionEntrada']);
            }
            if (!empty($this->justificacionSalida)) {
                $adicionales['justificacionSalida'] = $this->justificacionSalida;
            } else {
                unset($adicionales['justificacionSalida']);
            }
            $asistencia->update([
                'adicionales' => !empty($adicionales) ? $adicionales : null
            ]);
            $this->cancelarEdicion();
            session()->flash('mensaje', 'Registro de asistencia actualizado de forma correcta.');
        }
    }
    public function calcularSueldoSemana($registrosSemana)
    {
        $sueldoDiario = 0;
        $descuentoTotal = 0;
        $sueldoSemanalBase = 0;
        foreach ($registrosSemana as $asistencia) {
            $adicionales = is_array($asistencia->adicionales) ? $asistencia->adicionales : json_decode($asistencia->adicionales, true);
            if (isset($adicionales['sueldoSemanalBase'])) {
                $sueldoSemanalBase = floatval($adicionales['sueldoSemanalBase']);
                break;
            }
        }
        if ($sueldoSemanalBase === 0) {
            $primerRegistro = $registrosSemana->first();
            if ($primerRegistro) {
                $user = User::find($primerRegistro->IdUser);
                if ($user && isset($user->adicionales['sueldo'])) {
                    $sueldoSemanalBase = floatval($user->adicionales['sueldo']);
                }
            }
        }
        if ($sueldoSemanalBase > 0) {
            $sueldoDiario = $sueldoSemanalBase / 7;
            $tablaPenas = DB::table('penas')->pluck('descuentoDias', 'id')->toArray();
            foreach ($registrosSemana as $asistencia) {
                if (isset($asistencia->adicionales)) {
                    $datosAdicionales = is_array($asistencia->adicionales) ? $asistencia->adicionales : json_decode($asistencia->adicionales, true);
                    if (isset($datosAdicionales['penaEntradaId']) && isset($tablaPenas[$datosAdicionales['penaEntradaId']])) {
                        $descuentoTotal += $sueldoDiario * floatval($tablaPenas[$datosAdicionales['penaEntradaId']]);
                    }
                    if (isset($datosAdicionales['penaSalidaId']) && isset($tablaPenas[$datosAdicionales['penaSalidaId']])) {
                        $descuentoTotal += $sueldoDiario * floatval($tablaPenas[$datosAdicionales['penaSalidaId']]);
                    }
                }
            }
        }
        return [
            'sueldoDiario' => $sueldoDiario,
            'descuentoTotal' => $descuentoTotal,
            'sueldoNeto' => $sueldoSemanalBase - $descuentoTotal
        ];
    }
    public function registrarAsistencia($IdUser, $coordenadasActuales)
    {
        $configuracion = Util::getArrayJS('parametrosAsistencia');
        $reglas = is_array($configuracion) ? reset($configuracion) : null;
        if (empty($reglas)) {
            session()->flash('error', 'No se encontraron los parámetros de asistencia configurados. Por favor, captúrelos antes de continuar.');
            return;
        }
        $clavesObligatorias = [
            'distanciaMaximaMetros',
            'toleranciaMinutos',
            'horaInicioEntrada',
            'horaCorteEntrada',
            'horaFinSalida',
            'lunesViernesHoraEntrada',
            'lunesViernesHoraSalida',
            'sabadoHoraEntrada',
            'sabadoHoraSalida'
        ];
        foreach ($clavesObligatorias as $clave) {
            if (!isset($reglas[$clave])) {
                session()->flash('error', 'Falta el parámetro obligatorio "' . $clave . '" en la configuración. Por favor, captúrelo para poder continuar.');
                return;
            }
        }
        $zonaHoraria = 'America/Mexico_City';
        $fechaActual = Carbon::now($zonaHoraria)->toDateString();
        $horaActual = Carbon::now($zonaHoraria)->toTimeString();
        $esSabado = Carbon::now($zonaHoraria)->isSaturday();
        $registroActual = Carbon::parse($fechaActual . ' ' . $horaActual, $zonaHoraria);
        $horaInicioTurno = Carbon::parse($fechaActual . ' ' . $reglas['horaInicioEntrada'], $zonaHoraria);
        $horaFinTurno = Carbon::parse($fechaActual . ' ' . $reglas['horaFinSalida'], $zonaHoraria);
        if ($registroActual->lessThan($horaInicioTurno) || $registroActual->greaterThan($horaFinTurno)) {
            session()->flash('error', 'El registro de asistencia no está disponible en este momento de acuerdo al horario establecido.');
            return;
        }
        $asistenciaExistente = Asistencia::where('IdUser', $IdUser)
            ->where('fecha', $fechaActual)
            ->first();
        $casasAsignadas = DB::table('asignacions')
            ->join('casas', 'asignacions.IdCasa', '=', 'casas.id')
            ->where('asignacions.IdUser', $IdUser)
            ->select('casas.ubicacion')
            ->get();
        $ubicacionValida = false;
        foreach ($casasAsignadas as $casa) {
            if ($this->calcularDistancia($coordenadasActuales, $casa->ubicacion) <= $reglas['distanciaMaximaMetros']) {
                $ubicacionValida = true;
                break;
            }
        }
        $esEntrada = false;
        $horaCorteTurno = Carbon::parse($fechaActual . ' ' . $reglas['horaCorteEntrada'], $zonaHoraria);
        if (!$asistenciaExistente) {
            $esEntrada = true;
        } elseif ($registroActual->greaterThan($horaCorteTurno) && $registroActual->lessThanOrEqualTo($horaFinTurno)) {
            $esEntrada = false;
        } elseif ($registroActual->greaterThanOrEqualTo($horaInicioTurno) && $registroActual->lessThanOrEqualTo($horaCorteTurno)) {
            $esEntrada = true;
        }
        $horaEntradaRegla = $esSabado ? $reglas['sabadoHoraEntrada'] : $reglas['lunesViernesHoraEntrada'];
        $horaSalidaRegla = $esSabado ? $reglas['sabadoHoraSalida'] : $reglas['lunesViernesHoraSalida'];
        $tipoMensaje = 'mensaje';
        if ($esEntrada) {
            if ($asistenciaExistente && $asistenciaExistente->horaEnt !== '00:00:00' && !empty($asistenciaExistente->ubiEnt)) {
                session()->flash('error', 'Ya existe entrada para el día de hoy.');
                return;
            }
            $horaBaseEntrada = Carbon::parse($fechaActual . ' ' . $horaEntradaRegla, $zonaHoraria);
            $horaLimiteEntrada = $horaBaseEntrada->copy()->addMinutes($reglas['toleranciaMinutos']);
            $datosAdicionales = [];
            $motivosPena = [];
            $fueraDeHorario = $registroActual->greaterThan($horaLimiteEntrada);
            if (!$ubicacionValida) {
                $motivosPena[] = 'no estás cerca de alguna casa asignada';
            }
            if ($fueraDeHorario) {
                $motivosPena[] = 'has llegado tarde';
            }
            if (!empty($motivosPena)) {
                $datosAdicionales['penaEntradaId'] = 1;
                if (!empty($this->justificacion)) {
                    $datosAdicionales['justificacionEntrada'] = $this->justificacion;
                }
                $mensajeAviso = 'Penalización 1 día, ' . implode(' y ', $motivosPena) . '.';
                $tipoMensaje = 'error';
            } else {
                $mensajeAviso = 'Entrada registrada correctamente.';
            }
            $inicioSemana = Carbon::parse($fechaActual, $zonaHoraria)->startOfWeek()->toDateString();
            $finSemana = Carbon::parse($fechaActual, $zonaHoraria)->endOfWeek()->toDateString();
            $tieneRegistroSemana = Asistencia::where('IdUser', $IdUser)
                ->whereBetween('fecha', [$inicioSemana, $finSemana])
                ->exists();
            if (!$tieneRegistroSemana) {
                $user = User::find($IdUser);
                if ($user && isset($user->adicionales['sueldo'])) {
                    $datosAdicionales['sueldoSemanalBase'] = floatval($user->adicionales['sueldo']);
                }
            }
            Asistencia::create([
                'IdUser' => $IdUser,
                'fecha' => $fechaActual,
                'horaEnt' => $horaActual,
                'horaSal' => '00:00:00',
                'ubiEnt' => $coordenadasActuales,
                'ubiSal' => '',
                'adicionales' => !empty($datosAdicionales) ? $datosAdicionales : null,
            ]);
            $this->justificacion = '';
            session()->flash($tipoMensaje, $mensajeAviso);
            return;
        } else {
            if (!$asistenciaExistente) {
                session()->flash('error', 'No hay registro previo el día de hoy.');
                return;
            }
            if ($asistenciaExistente->horaSal !== '00:00:00' && !empty($asistenciaExistente->ubiSal)) {
                session()->flash('error', 'Hay registro previo de salida');
                return;
            }
            $horaBaseSalida = Carbon::parse($fechaActual . ' ' . $horaSalidaRegla, $zonaHoraria);
            $horaMinimaSalida = $horaBaseSalida->copy()->subMinutes($reglas['toleranciaMinutos']);
            $datosAdicionales = $asistenciaExistente->adicionales ?? [];
            if (is_string($datosAdicionales)) {
                $datosAdicionales = json_decode($datosAdicionales, true) ?? [];
            }
            $fueraDeHorarioSalida = $registroActual->lessThan($horaMinimaSalida);
            if (!$ubicacionValida) {
                $datosAdicionales['penaSalidaId'] = 1;
                $mensajeAviso = 'Penalización 1 día, No estás cerca de alguna casa asignada.';
                $tipoMensaje = 'error';
            } elseif ($fueraDeHorarioSalida) {
                $datosAdicionales['penaSalidaId'] = 2;
                $mensajeAviso = 'Penalización de medio día salida antes de tiempo.';
                $tipoMensaje = 'error';
            } else {
                $mensajeAviso = 'Salida registrada correctamente.';
            }
            if (!$ubicacionValida || $fueraDeHorarioSalida) {
                if (!empty($this->justificacion)) {
                    $datosAdicionales['justificacionSalida'] = $this->justificacion;
                }
            }
            $asistenciaExistente->update([
                'horaSal' => $horaActual,
                'ubiSal' => $coordenadasActuales,
                'adicionales' => !empty($datosAdicionales) ? $datosAdicionales : null,
            ]);
            $this->justificacion = '';
            session()->flash($tipoMensaje, $mensajeAviso);
            return;
        }
    }
    private function calcularDistancia($coordenadasOrigen, $coordenadasDestino)
    {
        $origen = explode(',', $coordenadasOrigen);
        $destino = explode(',', $coordenadasDestino);
        if (count($origen) !== 2 || count($destino) !== 2) {
            return 99999;
        }
        $radioTierra = 6371000;
        $latitudOrigen = deg2rad(floatval($origen[0]));
        $longitudOrigen = deg2rad(floatval($origen[1]));
        $latitudDestino = deg2rad(floatval($destino[0]));
        $longitudDestino = deg2rad(floatval($destino[1]));
        $diferenciaLatitud = $latitudDestino - $latitudOrigen;
        $diferenciaLongitud = $longitudDestino - $longitudOrigen;
        $operacionA = sin($diferenciaLatitud / 2) * sin($diferenciaLatitud / 2) + cos($latitudOrigen) * cos($latitudDestino) * sin($diferenciaLongitud / 2) * sin($diferenciaLongitud / 2);
        $operacionC = 2 * atan2(sqrt($operacionA), sqrt(1 - $operacionA));
        return $radioTierra * $operacionC;
    }
public function imprimirNomina()
{
    $asistencias = Asistencia::with('user')->get();
    if ($asistencias->isEmpty()) {
        return null;
    }
    $semanas = $asistencias->groupBy(function($item) {
        return Carbon::parse($item->fecha)->format('Y-W');
    })->sortKeysDesc()->take(2);
    $semanasProcesadas = collect();
    foreach ($semanas as $llaveSemana => $registrosSemana) {
        $primerDia = Carbon::parse($registrosSemana->first()->fecha)->startOfWeek();
        $ultimoDia = Carbon::parse($registrosSemana->first()->fecha)->endOfWeek();
        $registrosPorUsuario = $registrosSemana->groupBy('IdUser');
        $usuariosPagos = collect();
        foreach ($registrosPorUsuario as $idUsuario => $asistenciasUsuario) {
            $usuario = $asistenciasUsuario->first()->user;
            if (!$usuario) {
                continue;
            }
            $resumenSueldo = $this->calcularSueldoSemana($asistenciasUsuario);
            $sueldoSemanalBase = $resumenSueldo['sueldoNeto'] + $resumenSueldo['descuentoTotal'];
            $usuariosPagos->push([
                'nombre' => $usuario->name,
                'descuentoTotal' => $resumenSueldo['descuentoTotal'],
                'sueldoNeto' => $resumenSueldo['sueldoNeto'],
                'sueldoSemanal' => $sueldoSemanalBase
            ]);
        }
        $semanasProcesadas->push([
            'rango' => 'Semana: del ' . $primerDia->format('d/m/Y') . ' al ' . $ultimoDia->format('d/m/Y'),
            'usuarios' => $usuariosPagos,
            'totalDescuentos' => $usuariosPagos->sum('descuentoTotal'),
            'totalNeto' => $usuariosPagos->sum('sueldoNeto')
        ]);
    }
    $htmlNomina = view('livewire.asistencias.nominaPDF', compact('semanasProcesadas'))->render();
    $instanciaDompdf = PDF::loadHTML($htmlNomina);
    $instanciaDompdf->setPaper('letter', 'portrait');
    $contenidoPdf = $instanciaDompdf->output();
    $rutaArchivo = 'nomina/semanaNom.pdf';
    Storage::disk('public')->put($rutaArchivo, $contenidoPdf);
    $rutaFisica = storage_path('app/public/' . $rutaArchivo);
    return response()->file($rutaFisica, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="semanaNom.pdf"'
    ]);
}
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
}