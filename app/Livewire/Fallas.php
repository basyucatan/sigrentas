<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Falla;
use Livewire\Attributes\Computed;
use App\Models\{Util};
use Illuminate\Support\Facades\DB;

class Fallas extends Component
{
    use WithPagination;
	protected $paginationTheme = 'bootstrap';
    public $verModalFalla=false, $selected_id, $keyWord, $falla;
	
    public function mount(){}
    public function updatedKeyWord(){$this->resetPage();}
    #[Computed]
	public function filteredFallas()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Falla::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('falla', 'LIKE', $keyWord);
			})
			->paginate(12);
	}
	public function render()
	{
		return view('livewire.fallas.view', [
			'fallas' => $this->filteredFallas,
		]);
	}
    public function cancel()
    {
        $this->resetInput();
        $this->verModalFalla = false;
    }
    public function resetInput()
    {
        $this->resetExcept('keyWord');
    }
    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Falla::findOrFail($id)->toArray());
        $this->verModalFalla = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalFalla = true;
    }    
    public function save()
    {
        $this->validate([
		'falla' => 'required',
        ]);

        Falla::updateOrCreate(
			['id' => $this->selected_id],
			[
				'falla' => $this-> falla
			]
		);
        $this->resetInput();
        $this->verModalFalla = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Falla::where('id', $id)->delete();
        }
    }
}