<?php
namespace App\Livewire;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use App\Models\Gasto;
use App\Models\User;
use App\Models\Util;
use App\Services\ServicioIA;
use Illuminate\Support\Facades\Auth;
class Gastos extends Component
{
    use WithPagination;
    use WithFileUploads;
    protected $paginationTheme = 'bootstrap';
    public $verModalGasto = false;
    public $selected_id;
    public $keyWord = '';
    public $modoVista = 'mis_gastos';
    public $fechaInicio;
    public $fechaFin;
    public $totalCalculadoIA = null;
    public $IdEfectuo;
    public $IdAutorizo;
    public $estatus = 'pendiente';
    public $monto;
    public $fecha;
    public $foto;
    public $archivoTemp;
    public $adicionales = [];
    public $listaEstatus = [];
    public $listaUsuarios = [];
    public $procesandoIA = false;
    public function mount()
    {
        $this->listaEstatus = Util::getArray('gastos', 'estatus');
        $this->listaUsuarios = User::pluck('name', 'id')->toArray();
        $this->fecha = date('Y-m-d');
        $this->fechaInicio = date('Y-m-01');
        $this->fechaFin = date('Y-m-d');
    }
    public function updatedArchivoTemp()
    {
        if ($this->archivoTemp) {
            $this->procesandoIA = true;
            $rutaTemporal = $this->archivoTemp->getRealPath();
            $datosIA = ServicioIA::analizarComprobante($rutaTemporal);
            if ($datosIA && isset($datosIA['notas']) && count($datosIA['notas']) > 0) {
                $primeraNota = $datosIA['notas'][0];
                $this->monto = $primeraNota['monto'] ?? $this->monto;
                $this->fecha = $primeraNota['fecha'] ?? $this->fecha;
                $this->adicionales['concepto'] = $primeraNota['concepto'] ?? '';
                if (count($datosIA['notas']) > 1) {
                    $this->adicionales['notas_detectadas'] = $datosIA['notas'];
                }
            }
            $this->procesandoIA = false;
        }
    }
    public function updatedKeyWord()
    {
        $this->resetPage();
    }
    public function updatedModoVista()
    {
        $this->resetPage();
    }
    #[Computed]
    public function filteredGastos()
    {
        $keyWord = '%' . $this->keyWord . '%';
        $userId = Auth::id() ?? 1;
        return Gasto::where('id', '>', 0)
            ->when($this->modoVista == 'mis_gastos', function($q) use ($userId) {
                $q->where('IdEfectuo', $userId);
            })
            ->when($this->modoVista == 'por_autorizar', function($q) use ($userId) {
                $q->where('IdAutorizo', $userId)->where('estatus', 'pendiente');
            })
            ->where(function ($query) use ($keyWord) {
                $query->orWhere('monto', 'LIKE', $keyWord)
                    ->orWhere('estatus', 'LIKE', $keyWord)
                    ->orWhere('fecha', 'LIKE', $keyWord);
            })
            ->paginate(12);
    }
    public function calcularTotalIA()
    {
        $userId = Auth::id() ?? 1;
        $this->totalCalculadoIA = Gasto::where('IdEfectuo', $userId)
            ->whereBetween('fecha', [$this->fechaInicio, $this->fechaFin])
            ->whereIn('estatus', ['efectuado', 'autorizado'])
            ->sum('monto');
    }
    public function create()
    {
        $this->resetInput();
        $this->IdEfectuo = Auth::id() ?? 1;
        $this->verModalGasto = true;
    }
    public function edit($id)
    {
        $this->selected_id = $id;
        $gasto = Gasto::findOrFail($id);
        $this->fill($gasto->toArray());
        $this->verModalGasto = true;
    }
    public function save()
    {
        $this->validate([
            'fecha' => 'required|date',
            'estatus' => 'required'
        ]);
        $nombreFoto = $this->foto;
        if ($this->archivoTemp) {
            $nombreFoto = Util::guardarArchivo($this->archivoTemp, "gasto_" . time(), "gastos");
        }
        Gasto::updateOrCreate(
            ['id' => $this->selected_id],
            [
                'IdEfectuo' => $this->IdEfectuo,
                'IdAutorizo' => $this->IdAutorizo,
                'estatus' => $this->estatus,
                'monto' => $this->monto,
                'fecha' => $this->fecha,
                'foto' => $nombreFoto,
                'adicionales' => $this->adicionales
            ]
        );
        $this->cancel();
    }
    public function cancel()
    {
        $this->resetInput();
        $this->verModalGasto = false;
    }
    public function resetInput()
    {
        $this->resetExcept('keyWord', 'modoVista', 'fechaInicio', 'fechaFin', 'totalCalculadoIA', 'listaEstatus', 'listaUsuarios');
        $this->fecha = date('Y-m-d');
        $this->estatus = 'pendiente';
        $this->procesandoIA = false;
    }
    public function destroy($id)
    {
        if ($id) {
            Gasto::where('id', $id)->delete();
        }
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function render()
    {
        return view('livewire.gastos.view', [
            'gastos' => $this->filteredGastos,
        ]);
    }
}