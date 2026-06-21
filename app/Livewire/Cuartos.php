<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Cuarto;
use Livewire\Attributes\Computed;
use App\Models\{Util};
use Illuminate\Support\Facades\DB;

class Cuartos extends Component
{
    use WithPagination;
	protected $paginationTheme = 'bootstrap';
    public $verModalCuarto=false, $selected_id, $keyWord, $IdCasa, $cuarto, $estatus;
	
	public $adicionales = [];
    public function mount(){}
    public function updatedKeyWord(){$this->resetPage();}
    #[Computed]
	public function filteredCuartos()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Cuarto::Where('IdCasa', $this->IdCasa)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('IdCasa', 'LIKE', $keyWord)
						->orWhere('cuarto', 'LIKE', $keyWord)
						->orWhere('estatus', 'LIKE', $keyWord);
			})
			->paginate(12);
	}
	public function render()
	{
		return view('livewire.cuartos.view', [
			'cuartos' => $this->filteredCuartos,
		]);
	}
    public function cancel()
    {
        $this->resetInput();
        $this->verModalCuarto = false;
    }
    public function resetInput()
    {
        $this->resetExcept('keyWord');
    }
    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Cuarto::findOrFail($id)->toArray());
        $this->verModalCuarto = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalCuarto = true;
    }    
    public function save()
    {
        $this->validate([
		'IdCasa' => 'required',
		'cuarto' => 'required',
		'estatus' => 'required',
        ]);

        Cuarto::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdCasa' => $this-> IdCasa,
				'cuarto' => $this-> cuarto,
				'estatus' => $this-> estatus
			]
		);
        $this->resetInput();
        $this->verModalCuarto = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Cuarto::where('id', $id)->delete();
        }
    }
}