<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tecnico extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'tecnicos';

    protected $fillable = ['IdUser','IdVehiculo','tecnico','telefono','activo','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
	
    public function asignaciones()
    {
        return $this->hasMany('App\Models\Asignacione', 'IdTecnico', 'id');
    }
    
    public function tickets()
    {
        return $this->hasMany('App\Models\Ticket', 'IdTecnico', 'id');
    }
    
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'IdUser');
    }
    
    public function vehiculo()
    {
        return $this->hasOne('App\Models\Vehiculo', 'id', 'IdVehiculo');
    }
    
}
