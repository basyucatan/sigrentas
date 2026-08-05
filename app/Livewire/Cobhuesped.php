<?php
namespace App\Livewire;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\{Contrato, Recibo, Pago};
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Carbon\Carbon;
class Cobhuesped extends Component
{
    use WithFileUploads;
    public $IdRecibo, $idPagoEdicion, $montoPago, $fechaPago, $foto, $fotoActual, $verModalPago = false;
    public $filtroVista = 'proximo';
    public function mount()
    {
        $this->fechaPago = date('Y-m-d');
    }
    private function esInquilinoValido()
    {
        return auth()->user() && auth()->user()->hasRole('inquilino');
    }
    public function abrirModalPago($idRecibo)
    {
        if (!$this->esInquilinoValido()) {return;}
        $recibo = Recibo::whereHas('contrato.inquilino', fn($q) => $q->where('IdUser', auth()->id()))
            ->with('pagos')
            ->findOrFail($idRecibo);
        $this->IdRecibo = $recibo->id;
        $this->idPagoEdicion = null;
        $totalPagado = $recibo->pagos->sum('montoPago');
        $saldo = $recibo->montoRenta - $totalPagado;
        $this->montoPago = $saldo > 0 ? $saldo : 0;
        $this->fechaPago = date('Y-m-d');
        $this->foto = null;
        $this->fotoActual = null;
        $this->verModalPago = true;
    }
    public function abrirModalFoto($idPago)
    {
        if (!$this->esInquilinoValido()) {return;}
        $pago = Pago::whereHas('recibo.contrato.inquilino', fn($q) => $q->where('IdUser', auth()->id()))
            ->findOrFail($idPago);
        $this->idPagoEdicion = $pago->id;
        $this->IdRecibo = $pago->IdRecibo;
        $this->montoPago = $pago->montoPago;
        $this->fechaPago = $pago->fecha;
        $this->foto = null;
        $this->fotoActual = $pago->adicionales['foto'] ?? null;
        $this->verModalPago = true;
    }
public function guardarPago()
{
    if (!$this->esInquilinoValido()) {return;}

    if ($this->idPagoEdicion) {
        $this->validate([
            'foto' => 'required|image|max:4096'
        ]);

        $pago = Pago::whereHas('recibo.contrato.inquilino', fn($q) => $q->where('IdUser', auth()->id()))
            ->findOrFail($this->idPagoEdicion);

        $adicionales = $pago->adicionales ?? [];
        
        if (isset($adicionales['foto'])) {
            Storage::disk('public')->delete($adicionales['foto']);
        }

        $adicionales['foto'] = $this->foto->store('comprobantes', 'public');
        
        // Solo actualizamos la foto en los adicionales, preservando montoPago y fecha intactos
        $pago->update(['adicionales' => $adicionales]);
    } else {
        $this->validate([
            'IdRecibo' => 'required',
            'montoPago' => 'required|numeric|min:0.01',
            'fechaPago' => 'required|date',
            'foto' => 'nullable|image|max:4096'
        ]);

        $recibo = Recibo::whereHas('contrato.inquilino', fn($q) => $q->where('IdUser', auth()->id()))
            ->findOrFail($this->IdRecibo);

        $adicionales = [];
        if ($this->foto) {
            $adicionales['foto'] = $this->foto->store('comprobantes', 'public');
        }

        Pago::create([
            'IdRecibo' => $recibo->id,
            'montoPago' => $this->montoPago,
            'fecha' => $this->fechaPago,
            'adicionales' => $adicionales
        ]);
    }

    $this->verModalPago = false;
    $this->reset(['IdRecibo', 'idPagoEdicion', 'montoPago', 'foto', 'fotoActual']);
}
    #[Computed]
    public function todosLosRecibos()
    {
        if (!$this->esInquilinoValido()) {return collect([]);}
        return Recibo::whereHas('contrato.inquilino', fn($q) => $q->where('IdUser', auth()->id()))
            ->with(['pagos', 'contrato.cuarto.casa'])
            ->orderBy('fechaVence', 'asc')
            ->get();
    }
    #[Computed]
    public function analiticaRecibos()
    {
        $recibos = $this->todosLosRecibos;
        $pendientes = collect([]);
        $pagados = collect([]);
        $proximoRecibo = null;
        $hoy = Carbon::today();

        foreach ($recibos as $recibo) {
            $totalPagado = $recibo->pagos->sum('montoPago');
            $saldo = $recibo->montoRenta - $totalPagado;
            if ($saldo > 0) {
                $pendientes->push($recibo);
                if (!$proximoRecibo) {
                    $proximoRecibo = $recibo;
                }
            } else {
                $pagados->push($recibo);
            }
        }

        $estadoSemaforo = 'al_dia';
        $diasDiferencia = 0;

        if ($proximoRecibo) {
            $fechaVence = Carbon::parse($proximoRecibo->fechaVence);
            $diasDiferencia = $hoy->diffInDays($fechaVence, false);

            if ($diasDiferencia < 0) {
                $estadoSemaforo = 'vencido';
            } elseif ($diasDiferencia <= 3) {
                $estadoSemaforo = 'por_vencer';
            } else {
                $estadoSemaforo = 'al_dia';
            }
        }

        return [
            'proximoRecibo' => $proximoRecibo,
            'pendientes' => $pendientes,
            'pagados' => $pagados,
            'estadoSemaforo' => $estadoSemaforo,
            'diasDiferencia' => abs((int)$diasDiferencia)
        ];
    }
    public function render()
    {
        return view('livewire.cobhuesped.view', [
            'analitica' => $this->analiticaRecibos
        ]);
    }
}