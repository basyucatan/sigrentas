<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ticket;
use Livewire\Attributes\Computed;
use App\Models\{Util};
use Illuminate\Support\Facades\DB;

class Tickets extends Component
{
    use WithPagination;
	protected $paginationTheme = 'bootstrap';
    public $verModalTicket=false, $selected_id, $keyWord, $IdCuarto, $IdFalla, $IdAutor, $IdTecnico, $tipo, $estatus, $ticket, $fechaSol, $fechaFin;
	
	public $adicionales = [];
    public function mount(){}
    public function updatedKeyWord(){$this->resetPage();}
    #[Computed]
	public function filteredTickets()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Ticket::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('IdCuarto', 'LIKE', $keyWord)
						->orWhere('IdFalla', 'LIKE', $keyWord)
						->orWhere('IdAutor', 'LIKE', $keyWord)
						->orWhere('IdTecnico', 'LIKE', $keyWord)
						->orWhere('tipo', 'LIKE', $keyWord)
						->orWhere('estatus', 'LIKE', $keyWord)
						->orWhere('ticket', 'LIKE', $keyWord)
						->orWhere('fechaSol', 'LIKE', $keyWord)
						->orWhere('fechaFin', 'LIKE', $keyWord);
			})
			->paginate(12);
	}
	public function render()
	{
		return view('livewire.tickets.view', [
			'tickets' => $this->filteredTickets,
		]);
	}
    public function cancel()
    {
        $this->resetInput();
        $this->verModalTicket = false;
    }
    public function resetInput()
    {
        $this->resetExcept('keyWord');
    }
    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Ticket::findOrFail($id)->toArray());
        $this->verModalTicket = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalTicket = true;
    }    
    public function save()
    {
        $this->validate([
		'IdCuarto' => 'required',
		'tipo' => 'required',
		'estatus' => 'required',
		'ticket' => 'required',
		'fechaSol' => 'required',
        ]);

        Ticket::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdCuarto' => $this-> IdCuarto,
				'IdFalla' => $this-> IdFalla,
				'IdAutor' => $this-> IdAutor,
				'IdTecnico' => $this-> IdTecnico,
				'tipo' => $this-> tipo,
				'estatus' => $this-> estatus,
				'ticket' => $this-> ticket,
				'fechaSol' => $this-> fechaSol,
				'fechaFin' => $this-> fechaFin
			]
		);
        $this->resetInput();
        $this->verModalTicket = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Ticket::where('id', $id)->delete();
        }
    }
}