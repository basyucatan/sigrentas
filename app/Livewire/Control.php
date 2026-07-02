<?php
namespace App\Livewire;
use App\Models\{Ticket, Util, Tecnico, Cuarto};
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
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
            'fechaPro'    => $this->selected_id ? Ticket::find($this->selected_id)->fechaSol 
                : now()->tz('America/Mexico_City')->format('Y-m-d\TH:i'),
            'fechaSol'    => $this->selected_id ? Ticket::find($this->selected_id)->fechaSol 
                : now()->tz('America/Mexico_City')->format('Y-m-d\TH:i'),
        ]
    );
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
public function updatingIdCasaFiltro() { $this->resetPage(); }
public function updatingEstatusFiltro() { $this->resetPage(); }
public function updatingPrioridadFiltro() { $this->resetPage(); }
public function updatingIdTecnicoFiltro() { $this->resetPage(); }
public function updatingKeyWord() { $this->resetPage(); }
#[Computed]
public function getTicketsProperty()
{
    $query = Ticket::with(['cuarto.casa', 'tecnico']);

    if (!empty($this->keyWord)) {
        $text = '%' . $this->keyWord . '%';
        $query->where(function ($q) use ($text) {
            $q->where('ticket', 'LIKE', $text)
                ->orWhere('id', $this->keyWord)
                ->orWhere('estatus', 'LIKE', $text)
                ->orWhereHas('cuarto.casa', fn($sub) => $sub->where('casa', 'LIKE', $text))
                ->orWhereHas('falla', fn($sub) => $sub->where('falla', 'LIKE', $text))
                ->orWhereHas('tecnico', fn($sub) => $sub->where('tecnico', 'LIKE', $text));
        });
    }

    if (!empty($this->IdCasaFiltro)) {
        $query->whereHas('cuarto', fn($q) => $q->where('IdCasa', $this->IdCasaFiltro));
    }

    if (!empty($this->estatusFiltro)) {
        $query->where('estatus', $this->estatusFiltro);
    }

    if (!empty($this->prioridadFiltro)) {
        $query->where('IdPrioridad', $this->prioridadFiltro);
    }

    if (!empty($this->IdTecnicoFiltro)) {
        $query->where('IdTecnico', $this->IdTecnicoFiltro);
    }

    return $query->latest()->paginate(10);
}
    public function render()
    {
        return view('livewire.control.view', [
            'tickets' => $this->tickets
        ]);
    }
}