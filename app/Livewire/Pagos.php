<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pago;
use Livewire\Attributes\Computed;
use App\Models\{Util};
use Illuminate\Support\Facades\DB;

class Pagos extends Component
{
    use WithPagination;
	protected $paginationTheme = 'bootstrap';
    public $verModalPago=false, $selected_id, $keyWord, $IdRecibo, $montoPago, $fecha;
	
	public $adicionales = [];
    public function mount(){}
    public function updatedKeyWord(){$this->resetPage();}
    #[Computed]
	public function filteredPagos()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Pago::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('IdRecibo', 'LIKE', $keyWord)
						->orWhere('montoPago', 'LIKE', $keyWord)
						->orWhere('fecha', 'LIKE', $keyWord);
			})
			->paginate(12);
	}
	public function render()
	{
		return view('livewire.pagos.view', [
			'pagos' => $this->filteredPagos,
		]);
	}
    public function cancel()
    {
        $this->resetInput();
        $this->verModalPago = false;
    }
    public function resetInput()
    {
        $this->resetExcept('keyWord');
    }
    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Pago::findOrFail($id)->toArray());
        $this->verModalPago = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalPago = true;
    }    
    public function save()
    {
        $this->validate([
		'IdRecibo' => 'required',
		'montoPago' => 'required',
		'fecha' => 'required',
        ]);

        Pago::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdRecibo' => $this-> IdRecibo,
				'montoPago' => $this-> montoPago,
				'fecha' => $this-> fecha
			]
		);
        $this->resetInput();
        $this->verModalPago = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Pago::where('id', $id)->delete();
        }
    }
}