<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'asignacions';

    protected $fillable = ['IdCasa','IdTecnico'];
    
	
    public function casa()
    {
        return $this->hasOne('App\Models\Casa', 'id', 'IdCasa');
    }
    
    public function tecnico()
    {
        return $this->hasOne('App\Models\Tecnico', 'id', 'IdTecnico');
    }
    
}
