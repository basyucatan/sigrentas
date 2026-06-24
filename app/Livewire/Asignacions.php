<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Asignacion;
use Livewire\Attributes\Computed;
use App\Models\{Util};
use Illuminate\Support\Facades\DB;

class Asignacions extends Component
{
    use WithPagination;
	protected $paginationTheme = 'bootstrap';
    public $verModalAsignacion=false, $selected_id, $keyWord, $IdCasa, $IdTecnico;
	
    public function mount(){}
    public function updatedKeyWord(){$this->resetPage();}
    #[Computed]
	public function filteredAsignacions()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Asignacion::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('IdCasa', 'LIKE', $keyWord)
						->orWhere('IdTecnico', 'LIKE', $keyWord);
			})
			->paginate(12);
	}
	public function render()
	{
		return view('livewire.asignacions.view', [
			'asignacions' => $this->filteredAsignacions,
		]);
	}
    public function cancel()
    {
        $this->resetInput();
        $this->verModalAsignacion = false;
    }
    public function resetInput()
    {
        $this->resetExcept('keyWord');
    }
    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Asignacion::findOrFail($id)->toArray());
        $this->verModalAsignacion = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalAsignacion = true;
    }    
    public function save()
    {
        $this->validate([
		'IdCasa' => 'required',
		'IdTecnico' => 'required',
        ]);

        Asignacion::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdCasa' => $this-> IdCasa,
				'IdTecnico' => $this-> IdTecnico
			]
		);
        $this->resetInput();
        $this->verModalAsignacion = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Asignacion::where('id', $id)->delete();
        }
    }
}