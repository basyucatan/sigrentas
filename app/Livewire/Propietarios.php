<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Propietario;
use Livewire\Attributes\Computed;
use App\Models\{Util};
use Illuminate\Support\Facades\DB;

class Propietarios extends Component
{
    use WithPagination;
	protected $paginationTheme = 'bootstrap';
    public $verModalPropietario=false, $selected_id, $keyWord, $propietario, $generales;
	
	public $adicionales = [];
    public function mount(){}
    public function updatedKeyWord(){$this->resetPage();}
    #[Computed]
	public function filteredPropietarios()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Propietario::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('propietario', 'LIKE', $keyWord)
						->orWhere('generales', 'LIKE', $keyWord);
			})
			->paginate(12);
	}
	public function render()
	{
		return view('livewire.propietarios.view', [
			'propietarios' => $this->filteredPropietarios,
		]);
	}
    public function cancel()
    {
        $this->resetInput();
        $this->verModalPropietario = false;
    }
    public function resetInput()
    {
        $this->resetExcept('keyWord');
    }
    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Propietario::findOrFail($id)->toArray());
        $this->verModalPropietario = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalPropietario = true;
    }    
    public function save()
    {
        $this->validate([
		'propietario' => 'required',
        ]);

        Propietario::updateOrCreate(
			['id' => $this->selected_id],
			[
				'propietario' => $this-> propietario,
				'generales' => $this-> generales
			]
		);
        $this->resetInput();
        $this->verModalPropietario = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Propietario::where('id', $id)->delete();
        }
    }
}