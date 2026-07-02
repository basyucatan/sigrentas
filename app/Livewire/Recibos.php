<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Recibo;
use Livewire\Attributes\Computed;
use App\Models\{Util};
use Illuminate\Support\Facades\DB;

class Recibos extends Component
{
    use WithPagination;
	protected $paginationTheme = 'bootstrap';
    public $verModalRecibo=false, $selected_id, $keyWord, $IdContrato, $montoRenta, $fechaVence;
	
	public $adicionales = [];
    public function mount(){}
    public function updatedKeyWord(){$this->resetPage();}
    #[Computed]
	public function filteredRecibos()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Recibo::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('IdContrato', 'LIKE', $keyWord)
						->orWhere('montoRenta', 'LIKE', $keyWord)
						->orWhere('fechaVence', 'LIKE', $keyWord);
			})
			->paginate(12);
	}
	public function render()
	{
		return view('livewire.recibos.view', [
			'recibos' => $this->filteredRecibos,
		]);
	}
    public function cancel()
    {
        $this->resetInput();
        $this->verModalRecibo = false;
    }
    public function resetInput()
    {
        $this->resetExcept('keyWord');
    }
    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Recibo::findOrFail($id)->toArray());
        $this->verModalRecibo = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalRecibo = true;
    }    
    public function save()
    {
        $this->validate([
		'IdContrato' => 'required',
		'montoRenta' => 'required',
		'fechaVence' => 'required',
        ]);

        Recibo::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdContrato' => $this-> IdContrato,
				'montoRenta' => $this-> montoRenta,
				'fechaVence' => $this-> fechaVence
			]
		);
        $this->resetInput();
        $this->verModalRecibo = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Recibo::where('id', $id)->delete();
        }
    }
}