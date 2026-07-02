<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Invmueble;
use Livewire\Attributes\Computed;
use App\Models\{Util};
use Illuminate\Support\Facades\DB;

class Invmuebles extends Component
{
    use WithPagination;
	protected $paginationTheme = 'bootstrap';
    public $verModalInvmueble=false, $selected_id, $keyWord, $IdCuarto, $mueble, $estatus;
	
	public $adicionales = [];
    public function mount(){}
    public function updatedKeyWord(){$this->resetPage();}
    #[Computed]
	public function filteredInvmuebles()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Invmueble::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('IdCuarto', 'LIKE', $keyWord)
						->orWhere('mueble', 'LIKE', $keyWord)
						->orWhere('estatus', 'LIKE', $keyWord);
			})
			->paginate(12);
	}
	public function render()
	{
		return view('livewire.invmuebles.view', [
			'invmuebles' => $this->filteredInvmuebles,
		]);
	}
    public function cancel()
    {
        $this->resetInput();
        $this->verModalInvmueble = false;
    }
    public function resetInput()
    {
        $this->resetExcept('keyWord');
    }
    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Invmueble::findOrFail($id)->toArray());
        $this->verModalInvmueble = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalInvmueble = true;
    }    
    public function save()
    {
        $this->validate([
		'IdCuarto' => 'required',
		'mueble' => 'required',
		'estatus' => 'required',
        ]);

        Invmueble::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdCuarto' => $this-> IdCuarto,
				'mueble' => $this-> mueble,
				'estatus' => $this-> estatus
			]
		);
        $this->resetInput();
        $this->verModalInvmueble = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Invmueble::where('id', $id)->delete();
        }
    }
}