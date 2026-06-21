<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuarto extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'cuartos';

    protected $fillable = ['IdCasa','cuarto','estatus','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
	
    public function casa()
    {
        return $this->hasOne('App\Models\Casa', 'id', 'IdCasa');
    }
    
    public function contratos()
    {
        return $this->hasMany('App\Models\Contrato', 'IdCuarto', 'id');
    }
    
    public function tickets()
    {
        return $this->hasMany('App\Models\Ticket', 'IdCuarto', 'id');
    }
    
}
