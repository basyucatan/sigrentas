<?php
namespace App\Livewire;
use App\Models\{Ticket, Util, Tecnico, Cuarto};
use Livewire\Component;
use Livewire\WithPagination;

class Control extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $keyWord = '', $IdCasaFiltro, $estatusFiltro, $prioridadFiltro, $IdTecnicoFiltro,
    $verModalTicket = false, $verModalControl = false, $selected_id, 
    $IdCasa, $IdCuarto,$IdFalla, $tipo, $ticket, $IdTecnico, $IdPrioridad, $estatus;
    public $casas=[], $fallas=[], $cuartos =[], $prioridads=[], $tecnicos=[], $lEstatus=[], $tipos=[];

    public function mount()
    {
        $this->casas = Util::getArray('casas');
        $this->cuartos = Cuarto::all()->toArray();
        $this->fallas = Util::getArray('fallas');
        $this->tipos = Util::getArray('tickets', 'tipo');
        $this->lEstatus = Util::getArray('tickets', 'estatus');
        $this->tecnicos = Util::getArray('tecnicos');
        $this->prioridads = Util::getArray('prioridads');
        // $this->tecnicos = Tecnico::pluck('tecnico', 'id')->toArray();
    }

public function updatedIdCasa($id)
{
    $this->IdCuarto = null; 
    $this->cuartos = Cuarto::where('IdCasa', $id)->pluck('cuarto', 'id')->toArray();
}
public function updatedIdPrioridad($value)
{
    if ($value == 2) {
        $this->IdFalla = 6;
        $this->tipo = 'entrega';
    } else {
        if ($this->tipo == 'entrega') {
            $this->tipo = null;
        }
    }
}
public function save()
{
    $this->validate([
        'IdCuarto' => 'required',
        'ticket'   => 'required',
        'estatus'  => 'required',
        'IdFalla'  => 'required',
        'tipo'  => 'required',
        'IdPrioridad'  => 'required',
    ]);
    Ticket::updateOrCreate(
        ['id' => $this->selected_id], 
        [
            'IdCuarto'    => $this->IdCuarto,
            'IdFalla'     => $this->IdFalla,
            'tipo'        => $this->tipo,
            'ticket'      => $this->ticket,
            'IdPrioridad'   => $this->IdPrioridad,
            'IdTecnico'   => $this->IdTecnico,
            'estatus'     => $this->estatus,
            'fechaSol'    => $this->selected_id ? Ticket::find($this->selected_id)->fechaSol 
                : now()->tz('America/Mexico_City')->format('Y-m-d\TH:i'),
        ]
    );

    // 3. Limpieza y cierre
    $this->cancelar();
}
    public function crearTicket()
    {
        $this->verModalTicket = true;
    }
public function edit($id)
    {
        $ticket = Ticket::findOrFail($id);
        $this->selected_id = $id;
        $this->fill($ticket->toArray());
        if ($ticket->cuarto) {
            $this->IdCasa = $ticket->cuarto->IdCasa;
            $this->cuartos = Cuarto::where('IdCasa', $this->IdCasa)->pluck('cuarto', 'id')->toArray();
        }
        $this->verModalTicket = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalTicket = true;
    } 
    public function cancelar()
    {
        $this->verModalTicket = false;
        $this->verModalControl = false;
        $this->resetInput();
    }
    public function resetInput()
    {
        $this->verModalTicket = true;
        $this->resetExcept(['keyWord','casas', 'cuartos', 'tipos', 'tecnicos', 
            'fallas','lEstatus','prioridads']);
    }

    public function render()
    {
        $tickets = Ticket::with(['cuarto.casa', 'tecnico'])
            ->when($this->keyWord, fn($q) => $q->where('ticket', 'like', "%{$this->keyWord}%"))
            ->latest()
            ->paginate(10);
        return view('livewire.control.view', compact('tickets'));
    }
}