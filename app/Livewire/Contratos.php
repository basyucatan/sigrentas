<?php
namespace App\Livewire;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Contrato;
use App\Models\Recibo;
use Livewire\Attributes\Computed;
use App\Models\{Util};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
class Contratos extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $verModalContrato=false, $verModalFirma=false, $selected_id, $keyWord, $IdCasa, $IdCuarto, $IdInquilino, $IdPropietario, $plazo, $fechaIni, $fechaFin, $montoRenta, $deposito, $penaEntrega, $docContrato, $docInvMuebles, $firma;
    public $casas=[], $adicionales = [], $cuartos =[], $inquilinos =[], $propietarios =[];
    public function mount()
    {
        $this->casas = Util::getArray('casas');
        $this->cuartos = Util::getArray('cuartos');
        $this->inquilinos = Util::getArray('inquilinos');
        $this->propietarios = Util::getArray('propietarios');
    }
    public function elegirCasa()
    {
        if (!$this->IdCasa) {return [];}
        $this->cuartos = DB::table('cuartos')->where('IdCasa', $this->IdCasa)->pluck('cuarto','id')->toArray();
    }
    public function firmar($id)
    {
        $this->selected_id = $id;
        $this->verModalFirma = true;
    }
    public function generarRecibosIniciales($contrato)
    {
        if (Recibo::where('IdContrato', $contrato->id)->exists()) {return;}
        $fechaInicial = Carbon::parse($contrato->fechaIni);
        $fechaFinal = Carbon::parse($contrato->fechaFin);
        if ($contrato->deposito > 0) {
            Recibo::create([
                'IdContrato' => $contrato->id,
                'montoRenta' => $contrato->deposito,
                'fechaVence' => $contrato->fechaIni,
                'adicionales' => ['concepto' => 'Depósito en Garantía', 'tipo' => 'inicial']
            ]);
        }
        $montoContrato = $contrato->adicionales['montoContrato'] ?? 0;
        if ($montoContrato > 0) {
            Recibo::create([
                'IdContrato' => $contrato->id,
                'montoRenta' => $montoContrato,
                'fechaVence' => $contrato->fechaIni,
                'adicionales' => ['concepto' => 'Monto del Contrato', 'tipo' => 'inicial']
            ]);
        }
        $fechaAux = $fechaInicial->clone();
        $numeroMensualidad = 1;
        while ($fechaAux->lessThan($fechaFinal)) {
            Recibo::create([
                'IdContrato' => $contrato->id,
                'montoRenta' => $contrato->montoRenta,
                'fechaVence' => $fechaAux->format('Y-m-d'),
                'adicionales' => ['concepto' => 'Renta Mensualidad #' . $numeroMensualidad, 'tipo' => 'renta']
            ]);
            $fechaAux->addMonth();
            $numeroMensualidad++;
        }
    }
    public function guardarFirma($dataBase64)
    {
        if (!$this->selected_id || !$dataBase64) {return;}
        $imagen = str_replace('data:image/png;base64,', '', $dataBase64);
        $imagen = str_replace(' ', '+', $imagen);
        $nombreArchivo = 'firma_' . $this->selected_id . '_' . time() . '.png';
        Storage::disk('public')->put('contratos/' . $nombreArchivo, base64_decode($imagen));
        $contrato = Contrato::findOrFail($this->selected_id);
        if ($contrato->firma && Storage::disk('public')->exists('contratos/' . $contrato->firma)) {
            Storage::disk('public')->delete('contratos/' . $contrato->firma);
        }
        $contrato->update(['firma' => $nombreArchivo]);
        $this->generarRecibosIniciales($contrato);
        $this->verModalFirma = false;
        $this->selected_id = null;
    }
    public function imprimir($id)
    {
        $contrato = Contrato::with(['cuarto','cuarto.casa','propietario','inquilino'])->findOrFail($id);
        $rutaFirma = null;
        if ($contrato->firma && Storage::disk('public')->exists('contratos/' . $contrato->firma)) {
            $path = storage_path('app/public/contratos/' . $contrato->firma);
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $rutaFirma = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
        $pdf = Pdf::loadView('livewire.contratos.contratoPDF', [
            'contrato' => $contrato,
            'rutaFirma' => $rutaFirma
        ]);
        $pdf->setPaper('letter', 'portrait');
        return response()->streamDownload(fn() => print($pdf->output()), "contrato.pdf", ['Content-Type' => 'application/pdf']);
    }
    public function updatedKeyWord(){$this->resetPage();}
    #[Computed]
    public function filteredContratos()
    {
        $keyWord = '%' . $this->keyWord . '%';
        return Contrato::Where('id','>',0)
            ->where(function ($query) use ($keyWord) {
                $query
                        ->orWhere('IdCuarto', 'LIKE', $keyWord)
                        ->orWhere('IdInquilino', 'LIKE', $keyWord)
                        ->orWhere('IdPropietario', 'LIKE', $keyWord)
                        ->orWhere('fechaIni', 'LIKE', $keyWord)
                        ->orWhere('fechaFin', 'LIKE', $keyWord)
                        ->orWhere('montoRenta', 'LIKE', $keyWord)
                        ->orWhere('deposito', 'LIKE', $keyWord)
                        ->orWhere('penaEntrega', 'LIKE', $keyWord)
                        ->orWhere('docContrato', 'LIKE', $keyWord)
                        ->orWhere('docInvMuebles', 'LIKE', $keyWord)
                        ->orWhere('firma', 'LIKE', $keyWord);
            })
            ->paginate(12);
    }
    public function render()
    {
        return view('livewire.contratos.view', [
            'contratos' => $this->filteredContratos,
        ]);
    }
    public function cancel()
    {
        $this->resetInput();
        $this->verModalContrato = false;
        $this->verModalFirma = false;
    }
    public function resetInput()
    {
        $this->resetExcept('keyWord','casas','cuartos','inquilinos','propietarios');
    }
    public function edit($id)
    {
        $this->selected_id = $id;
        $this->fill(Contrato::findOrFail($id)->toArray());
        $this->IdCasa = DB::table('cuartos')->where('id', $this->IdCuarto)->first()?->IdCasa;
        $this->verModalContrato = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalContrato = true;
    }    
    public function save()
    {
        $this->validate([
        'IdCuarto' => 'required',
        'IdInquilino' => 'required',
        'IdPropietario' => 'required',
        'fechaIni' => 'required',
        'montoRenta' => 'required',
        'deposito' => 'required',
        'penaEntrega' => 'required',
        ]);
        Contrato::updateOrCreate(
            ['id' => $this->selected_id],
            [
                'IdCuarto' => $this-> IdCuarto,
                'IdInquilino' => $this-> IdInquilino,
                'IdPropietario' => $this-> IdPropietario,
                'fechaIni' => $this-> fechaIni,
                'fechaFin' => $this-> fechaFin,
                'montoRenta' => $this-> montoRenta,
                'deposito' => $this-> deposito,
                'penaEntrega' => $this-> penaEntrega,
                'docContrato' => $this-> docContrato,
                'docInvMuebles' => $this-> docInvMuebles,
                'adicionales' => $this->adicionales,
                'firma' => $this-> firma
            ]
        );
        $this->resetInput();
        $this->verModalContrato = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Contrato::where('id', $id)->delete();
        }
    }
}