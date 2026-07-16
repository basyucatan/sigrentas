<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Asignacion;
use App\Models\User;
use App\Models\Casa;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;

class Asignacions extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $IdUser, $buscadorUser = '', $buscadorCasa = '';

    public function updatedBuscadorUser()
    {
        $this->resetPage('pageUsers');
    }

    public function seleccionarUsuario($id)
    {
        $this->IdUser = $id;
        $this->buscadorCasa = '';
    }

    #[Computed]
    public function obtenerUsuarios()
    {
        return User::where('name', 'LIKE', '%' . $this->buscadorUser . '%')
            ->orWhere('email', 'LIKE', '%' . $this->buscadorUser . '%')
            ->orWhere('telefono', 'LIKE', '%' . $this->buscadorUser . '%')
            ->paginate(10, ['*'], 'pageUsers');
    }

    #[Computed]
    public function obtenerCasasDisponibles()
    {
        if (!$this->IdUser) {
            return collect();
        }
        $casasAsignadas = Asignacion::where('IdUser', $this->IdUser)->pluck('IdCasa');
        return Casa::whereNotIn('id', $casasAsignadas)
            ->where('casa', 'LIKE', '%' . $this->buscadorCasa . '%')
            ->get();
    }

    #[Computed]
    public function obtenerAsignacionesActuales()
    {
        if (!$this->IdUser) {
            return collect();
        }
        return Asignacion::where('IdUser', $this->IdUser)
            ->with('Casa')
            ->get();
    }

    public function asignarCasa($idCasa)
    {
        if (!$this->IdUser) return;
        Asignacion::updateOrCreate([
            'IdCasa' => $idCasa,
            'IdUser' => $this->IdUser
        ]);
    }

    public function desasignarCasa($idAsignacion)
    {
        Asignacion::destroy($idAsignacion);
    }

    public function asignarCasaATodos($idCasa)
    {
        $usuarios = User::pluck('id');
        foreach ($usuarios as $idU) {
            Asignacion::updateOrCreate([
                'IdCasa' => $idCasa,
                'IdUser' => $idU
            ]);
        }
    }

    public function render()
    {
        return view('livewire.asignacions.view', [
            'usuarios' => $this->obtenerUsuarios,
            'casasDisponibles' => $this->obtenerCasasDisponibles,
            'asignaciones' => $this->obtenerAsignacionesActuales,
        ]);
    }
}