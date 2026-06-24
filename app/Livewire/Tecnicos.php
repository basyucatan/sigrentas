<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tecnico;
use Livewire\Attributes\Computed;
use App\Models\{Util};
use Illuminate\Support\Facades\DB;

class Tecnicos extends Component
{
    use WithPagination;
	protected $paginationTheme = 'bootstrap';
    public $verModalTecnico=false, $selected_id, $keyWord, $IdUser, $IdVehiculo, $tecnico, $telefono, $activo;
	
	public $adicionales = [];
    public function mount(){}
    public function updatedKeyWord(){$this->resetPage();}
    #[Computed]
	public function filteredTecnicos()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Tecnico::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('IdUser', 'LIKE', $keyWord)
						->orWhere('IdVehiculo', 'LIKE', $keyWord)
						->orWhere('tecnico', 'LIKE', $keyWord)
						->orWhere('telefono', 'LIKE', $keyWord)
						->orWhere('activo', 'LIKE', $keyWord);
			})
			->paginate(12);
	}
	public function render()
	{
		return view('livewire.tecnicos.view', [
			'tecnicos' => $this->filteredTecnicos,
		]);
	}
    public function cancel()
    {
        $this->resetInput();
        $this->verModalTecnico = false;
    }
    public function resetInput()
    {
        $this->resetExcept('keyWord');
    }
    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Tecnico::findOrFail($id)->toArray());
        $this->verModalTecnico = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalTecnico = true;
    }    
    public function save()
    {
        $this->validate([
		'IdUser' => 'required',
		'IdVehiculo' => 'required',
		'tecnico' => 'required',
		'telefono' => 'required',
		'activo' => 'required',
        ]);

        Tecnico::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdUser' => $this-> IdUser,
				'IdVehiculo' => $this-> IdVehiculo,
				'tecnico' => $this-> tecnico,
				'telefono' => $this-> telefono,
				'activo' => $this-> activo
			]
		);
        $this->resetInput();
        $this->verModalTecnico = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Tecnico::where('id', $id)->delete();
        }
    }
}