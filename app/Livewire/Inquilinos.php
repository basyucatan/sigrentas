<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Inquilino;
use Livewire\Attributes\Computed;
use App\Models\{Util};
use Illuminate\Support\Facades\DB;

class Inquilinos extends Component
{
    use WithPagination;
	protected $paginationTheme = 'bootstrap';
    public $verModalInquilino=false, $selected_id, $keyWord, $IdUser, $inquilino, $telefono, $generales;
	
	public $adicionales = [];
    public function mount(){}
    public function updatedKeyWord(){$this->resetPage();}
    #[Computed]
	public function filteredInquilinos()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Inquilino::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('IdUser', 'LIKE', $keyWord)
						->orWhere('inquilino', 'LIKE', $keyWord)
						->orWhere('telefono', 'LIKE', $keyWord)
						->orWhere('generales', 'LIKE', $keyWord);
			})
			->paginate(12);
	}
	public function render()
	{
		return view('livewire.inquilinos.view', [
			'inquilinos' => $this->filteredInquilinos,
		]);
	}
    public function cancel()
    {
        $this->resetInput();
        $this->verModalInquilino = false;
    }
    public function resetInput()
    {
        $this->resetExcept('keyWord');
    }
    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Inquilino::findOrFail($id)->toArray());
        $this->verModalInquilino = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->IdUser = 6;
        $this->verModalInquilino = true;
    }    
    public function save()
    {
        $this->validate([
		'inquilino' => 'required',
		'telefono' => 'required',
        ]);

        Inquilino::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdUser' => $this-> IdUser ?? 6,
				'inquilino' => $this-> inquilino,
				'telefono' => $this-> telefono,
				'generales' => $this-> generales
			]
		);
        $this->resetInput();
        $this->verModalInquilino = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Inquilino::where('id', $id)->delete();
        }
    }
}