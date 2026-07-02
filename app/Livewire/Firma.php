<?php

namespace App\Livewire;
use Livewire\Component;
use App\Models\Util;
class Firma extends Component
{
    public $modelId, $modelo, $campo, $carpeta, $show = false;
    protected $listeners = ['abrirFirmaModal' => 'abrir'];
    public function abrir($modelId, $modelo, $campo, $carpeta)
    {
        $this->modelId = $modelId;
        $this->modelo = $modelo;
        $this->campo = $campo;
        $this->carpeta = $carpeta;
        $this->show = true;
    }
    public function guardarFirma($dataUrl)
    {
        $nombre = Util::guardarArchivo($dataUrl, "firma_{$this->modelId}", $this->carpeta, true);
        $clase = "App\\Models\\" . $this->modelo;
        $clase::find($this->modelId)->update([$this->campo => $nombre]);
        
        // Esto disparará el cierre en Alpine gracias a @entangle
        $this->show = false; 
        $this->dispatch('firmaActualizada');
    }
    public function render() { return view('firma'); }
}