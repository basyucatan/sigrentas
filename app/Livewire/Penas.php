<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pena;
use Livewire\Attributes\Computed;
use App\Models\{Util};
use Illuminate\Support\Facades\DB;

class Penas extends Component
{
    use WithPagination;
	protected $paginationTheme = 'bootstrap';
    public $verModalPena=false, $selected_id, $keyWord, $pena, $descuentoDias;
	
    public function mount(){}
    public function updatedKeyWord(){$this->resetPage();}
    #[Computed]
	public function filteredPenas()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Pena::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('pena', 'LIKE', $keyWord)
						->orWhere('descuentoDias', 'LIKE', $keyWord);
			})
			->paginate(12);
	}
	public function render()
	{
		return view('livewire.penas.view', [
			'penas' => $this->filteredPenas,
		]);
	}
    public function cancel()
    {
        $this->resetInput();
        $this->verModalPena = false;
    }
    public function resetInput()
    {
        $this->resetExcept('keyWord');
    }
    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Pena::findOrFail($id)->toArray());
        $this->verModalPena = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalPena = true;
    }    
    public function save()
    {
        $this->validate([
		'pena' => 'required',
		'descuentoDias' => 'required',
        ]);

        Pena::updateOrCreate(
			['id' => $this->selected_id],
			[
				'pena' => $this-> pena,
				'descuentoDias' => $this-> descuentoDias
			]
		);
        $this->resetInput();
        $this->verModalPena = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Pena::where('id', $id)->delete();
        }
    }
}