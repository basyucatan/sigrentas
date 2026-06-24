<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Prioridad;
use Livewire\Attributes\Computed;
use App\Models\{Util};
use Illuminate\Support\Facades\DB;

class Prioridads extends Component
{
    use WithPagination;
	protected $paginationTheme = 'bootstrap';
    public $verModalPrioridad=false, $selected_id, $keyWord, $prioridad, $diasTolerancia, $colorHex;
	
    public function mount(){}
    public function updatedKeyWord(){$this->resetPage();}
    #[Computed]
	public function filteredPrioridads()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Prioridad::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('prioridad', 'LIKE', $keyWord)
						->orWhere('diasTolerancia', 'LIKE', $keyWord)
						->orWhere('colorHex', 'LIKE', $keyWord);
			})
			->paginate(12);
	}
	public function render()
	{
		return view('livewire.prioridads.view', [
			'prioridads' => $this->filteredPrioridads,
		]);
	}
    public function cancel()
    {
        $this->resetInput();
        $this->verModalPrioridad = false;
    }
    public function resetInput()
    {
        $this->resetExcept('keyWord');
    }
    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Prioridad::findOrFail($id)->toArray());
        $this->verModalPrioridad = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalPrioridad = true;
    }    
    public function save()
    {
        $this->validate([
		'prioridad' => 'required',
		'diasTolerancia' => 'required',
		'colorHex' => 'required',
        ]);

        Prioridad::updateOrCreate(
			['id' => $this->selected_id],
			[
				'prioridad' => $this-> prioridad,
				'diasTolerancia' => $this-> diasTolerancia,
				'colorHex' => $this-> colorHex
			]
		);
        $this->resetInput();
        $this->verModalPrioridad = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Prioridad::where('id', $id)->delete();
        }
    }
}