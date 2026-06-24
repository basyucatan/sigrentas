<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Vehiculo;
use Livewire\Attributes\Computed;
use App\Models\{Util};
use Illuminate\Support\Facades\DB;

class Vehiculos extends Component
{
    use WithPagination;
	protected $paginationTheme = 'bootstrap';
    public $verModalVehiculo=false, $selected_id, $keyWord, $vehiculo, $numero, $estatus;
	
	public $adicionales = [];
    public function mount(){}
    public function updatedKeyWord(){$this->resetPage();}
    #[Computed]
	public function filteredVehiculos()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Vehiculo::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('vehiculo', 'LIKE', $keyWord)
						->orWhere('numero', 'LIKE', $keyWord)
						->orWhere('estatus', 'LIKE', $keyWord);
			})
			->paginate(12);
	}
	public function render()
	{
		return view('livewire.vehiculos.view', [
			'vehiculos' => $this->filteredVehiculos,
		]);
	}
    public function cancel()
    {
        $this->resetInput();
        $this->verModalVehiculo = false;
    }
    public function resetInput()
    {
        $this->resetExcept('keyWord');
    }
    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Vehiculo::findOrFail($id)->toArray());
        $this->verModalVehiculo = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalVehiculo = true;
    }    
    public function save()
    {
        $this->validate([
		'vehiculo' => 'required',
		'numero' => 'required',
		'estatus' => 'required',
        ]);

        Vehiculo::updateOrCreate(
			['id' => $this->selected_id],
			[
				'vehiculo' => $this-> vehiculo,
				'numero' => $this-> numero,
				'estatus' => $this-> estatus
			]
		);
        $this->resetInput();
        $this->verModalVehiculo = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Vehiculo::where('id', $id)->delete();
        }
    }
}