<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Contrato;
use Livewire\Attributes\Computed;
use App\Models\{Util};
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
class Contratos extends Component
{
    use WithPagination;
	protected $paginationTheme = 'bootstrap';
    public $verModalContrato=false, $selected_id, $keyWord, $IdCasa, $IdCuarto, $IdInquilino, $IdPropietario, 
		$plazo,
		$fechaIni, $fechaFin, $montoRenta, $deposito, $penaEntrega, $docContrato, $docInvMuebles, $firma;
	public $adicionales = [], $casas=[], $cuartos =[], $inquilinos =[], $propietarios =[],
		$plazos=['6','12','24','36','48','60'];
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
    public function imprimir($id)
    {
        $contrato = contrato::with(['cuarto','cuarto.casa','propietario','inquilino'])->findOrFail($id);
        $pdf = Pdf::loadView('livewire.contratos.contratoPDF', [
            'contrato' => $contrato,
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
		$this->plazo = \Carbon\Carbon::parse($this->fechaIni)
    		->diffInMonths(\Carbon\Carbon::parse($this->fechaFin));
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
		'plazo' => 'required',
		'montoRenta' => 'required',
		'deposito' => 'required',
		'penaEntrega' => 'required',
        ]);
		$this->fechaFin = \Carbon\Carbon::parse($this->fechaIni)
        ->addMonths((int) $this->plazo)
        ->toDateString();
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