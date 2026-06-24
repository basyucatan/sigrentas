<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'vehiculos';

    protected $fillable = ['vehiculo','numero','estatus','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
	
    public function tecnicos()
    {
        return $this->hasMany('App\Models\Tecnico', 'IdVehiculo', 'id');
    }
    
}
