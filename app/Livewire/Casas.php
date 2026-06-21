<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Casa;
use Livewire\Attributes\Computed;
use App\Models\{Util};
use Illuminate\Support\Facades\DB;

class Casas extends Component
{
    use WithPagination;
	protected $paginationTheme = 'bootstrap';
    public $verModalCasa=false, $selected_id, $keyWord, $casa, $direccion, $gmaps;
	
	public $adicionales = [];
    public function mount(){}
    public function updatedKeyWord(){$this->resetPage();}
    #[Computed]
	public function filteredCasas()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Casa::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('casa', 'LIKE', $keyWord)
						->orWhere('direccion', 'LIKE', $keyWord)
						->orWhere('gmaps', 'LIKE', $keyWord);
			})
			->paginate(12);
	}
	public function render()
	{
		return view('livewire.casas.view', [
			'casas' => $this->filteredCasas,
		]);
	}
    public function cancel()
    {
        $this->resetInput();
        $this->verModalCasa = false;
    }
    public function resetInput()
    {
        $this->resetExcept('keyWord');
    }
    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Casa::findOrFail($id)->toArray());
        $this->verModalCasa = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalCasa = true;
    }    
    public function save()
    {
        $this->validate([
		'casa' => 'required',
		'direccion' => 'required',
		'gmaps' => 'required',
        ]);

        Casa::updateOrCreate(
			['id' => $this->selected_id],
			[
				'casa' => $this-> casa,
				'direccion' => $this-> direccion,
				'gmaps' => $this-> gmaps
			]
		);
        $this->resetInput();
        $this->verModalCasa = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Casa::where('id', $id)->delete();
        }
    }
}