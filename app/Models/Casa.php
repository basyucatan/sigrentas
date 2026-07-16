<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Casa extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'casas';

    protected $fillable = ['casa','direccion','gmaps','ubicacion','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
	
    public function asignaciones()
    {
        return $this->hasMany('App\Models\Asignacione', 'IdCasa', 'id');
    }
    
    public function cuartos()
    {
        return $this->hasMany('App\Models\Cuarto', 'IdCasa', 'id');
    }
    
}
