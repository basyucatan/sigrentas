<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ticketsseg;
use Livewire\Attributes\Computed;
use App\Models\{Util};
use Illuminate\Support\Facades\DB;

class Ticketssegs extends Component
{
    use WithPagination;
	protected $paginationTheme = 'bootstrap';
    public $verModalTicketsseg=false, $selected_id, $keyWord, $IdTicket, $IdUser, $comentario;
	
    public function mount(){}
    public function updatedKeyWord(){$this->resetPage();}
    #[Computed]
	public function filteredTicketssegs()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Ticketsseg::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('IdTicket', 'LIKE', $keyWord)
						->orWhere('IdUser', 'LIKE', $keyWord)
						->orWhere('comentario', 'LIKE', $keyWord);
			})
			->paginate(12);
	}
	public function render()
	{
		return view('livewire.ticketssegs.view', [
			'ticketssegs' => $this->filteredTicketssegs,
		]);
	}
    public function cancel()
    {
        $this->resetInput();
        $this->verModalTicketsseg = false;
    }
    public function resetInput()
    {
        $this->resetExcept('keyWord');
    }
    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Ticketsseg::findOrFail($id)->toArray());
        $this->verModalTicketsseg = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalTicketsseg = true;
    }    
    public function save()
    {
        $this->validate([
		'IdTicket' => 'required',
		'IdUser' => 'required',
		'comentario' => 'required',
        ]);

        Ticketsseg::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdTicket' => $this-> IdTicket,
				'IdUser' => $this-> IdUser,
				'comentario' => $this-> comentario
			]
		);
        $this->resetInput();
        $this->verModalTicketsseg = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Ticketsseg::where('id', $id)->delete();
        }
    }
}