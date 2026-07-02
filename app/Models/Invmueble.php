<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invmueble extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'invmuebles';

    protected $fillable = ['IdCuarto','mueble','estatus','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
	
    public function cuarto()
    {
        return $this->hasOne('App\Models\Cuarto', 'id', 'IdCuarto');
    }
    
}
