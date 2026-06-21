<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\{Util, Casa, Cuarto};
use Illuminate\Support\Facades\DB;
use App\Traits\Utilfun;

class Arbolcasas extends Component
{
    use Utilfun, WithFileUploads;

    public $verModalCasa = false, $verModalCuarto = false;
    public $selected_id, $casa, $direccion, $adicionales = [];
    public $cuarto_id, $cuarto, $estatus, $gmaps;
    public $keyWord = '';
    public $arbol = [], $expandCasas = [];
    public $foto;

    protected $rules = [
        'casa' => 'required',
        'direccion' => 'required',
        'adicionales.noCuartos' => 'required|numeric'
    ];

    public function mount() { $this->cargarArbol(); }

    public function updatedKeyWord() { $this->cargarArbol(); }

    public function cargarArbol()
    {
        $this->arbol = Casa::with(['cuartos' => fn($q) => $q->orderBy('cuarto', 'asc')])
            ->when($this->keyWord, function ($q) {
                $q->where('casa', 'like', "%{$this->keyWord}%")
                  ->orWhereHas('cuartos', fn($sub) => $sub->where('cuarto', 'like', "%{$this->keyWord}%"));
            })
            ->orderBy('casa', 'asc')->get()->toArray();
    }

public function crearCasa()
    {
        $this->reset(['selected_id', 'casa', 'direccion', 'adicionales', 'foto']);
        $this->adicionales = ['noCuartos' => 1, 'coordenadas' => ''];
        $this->gmaps = '';
        $this->verModalCasa = true;
    }
    public function editarCasa($id)
    {
        $casa = Casa::findOrFail($id);
        $this->selected_id = $casa->id;
        $this->casa = $casa->casa;
        $this->direccion = $casa->direccion;
        $this->gmaps = $casa->gmaps;
        $this->adicionales = $casa->adicionales ?? ['noCuartos' => 0, 'coordenadas' => '', 'foto' => ''];
        $this->verModalCasa = true;
    }
public function guardarCasa()
{
    $this->validate([
        'casa' => 'required',
        'direccion' => 'required',
        'adicionales.noCuartos' => 'required|numeric'
    ]);
    $casa = Casa::findOrNew($this->selected_id);
    $casa->casa = $this->casa;
    $casa->direccion = $this->direccion;
    $casa->gmaps = $this->gmaps;
    if (!$casa->exists) {
        $casa->save();
    }
    $adicionalesTemporal = $casa->adicionales ?? [];
    $adicionalesTemporal['noCuartos'] = $this->adicionales['noCuartos'];
    $adicionalesTemporal['coordenadas'] = Util::getLonLat($casa->id, 'casas');
    if ($this->foto) {
        $extension = $this->foto->getClientOriginalExtension();
        $nombreArchivo = 'casa-' . $casa->id . '.' . $extension;
        $adicionalesTemporal['foto'] = $this->foto->storeAs('casas', $nombreArchivo, 'public');
    }
    $casa->adicionales = $adicionalesTemporal;
    $casa->save();
    $this->cancel();
    $this->cargarArbol();
    $this->alerta('Casa guardada correctamente');
}

    public function editarCuarto($id)
    {
        $cuarto = Cuarto::findOrFail($id);
        $this->cuarto_id = $cuarto->id;
        $this->cuarto = $cuarto->cuarto;
        $this->estatus = $cuarto->estatus;
        $this->verModalCuarto = true;
    }

public function guardarCuarto()
{
    $this->validate([
        'estatus' => 'required',
        'cuarto' => [
            'required',
            \Illuminate\Validation\Rule::unique('cuartos', 'cuarto')
                ->where('IdCasa', Cuarto::find($this->cuarto_id)->IdCasa)
                ->ignore($this->cuarto_id)
        ]
    ]);
    Cuarto::findOrFail($this->cuarto_id)->fill([
        'estatus' => $this->estatus,
        'cuarto' => $this->cuarto
    ])->save();
    $this->cancel();
    $this->cargarArbol();
    $this->alerta('Cuarto actualizado correctamente');
}
public function generarCuartos($idCasa)
{
    $casa = Casa::find($idCasa);
    $totalDeseado = $casa->adicionales['noCuartos'] ?? 0;
    $cuartosActuales = $casa->cuartos()->count();
    if ($cuartosActuales >= $totalDeseado) {
        $this->alerta('No hay cuartos nuevos por generar', 'info', 2000);
        return;
    }
    DB::transaction(function () use ($casa, $cuartosActuales, $totalDeseado) {
        collect(range($cuartosActuales + 1, $totalDeseado))
            ->each(fn($n) => $casa->cuartos()->create([
                'cuarto' => $n, 
                'estatus' => 'disponible'
            ]));
    });
    $this->cargarArbol();
    $this->alerta('Cuartos adicionales generados correctamente');
}
    public function eliminarCasa($id)
    {
        $casa = Casa::find($id);
        if ($casa->cuartos()->exists()) {
            $this->alerta('No se puede eliminar la casa porque tiene cuartos', 'error', 2500);
            return;
        }
        $casa->delete();
        $this->cargarArbol();
    }

    public function eliminarCuarto($id)
    {
        $cuarto = Cuarto::find($id);
        if ($cuarto->tickets()->exists()) {
            $this->alerta('Tiene tickets asociados', 'error', 2500);
            return;
        }
        $cuarto->delete();
        $this->cargarArbol();
    }

    public function toggleCasa($idCasa) { $this->expandCasas[$idCasa] = !($this->expandCasas[$idCasa] ?? false); }

    public function cancel()
    {
        $this->reset(['verModalCasa', 'verModalCuarto', 'selected_id', 'casa', 'direccion', 'adicionales', 'cuarto_id', 'foto']);
    }

    public function render() { return view('livewire.arbolcasas.view'); }
}