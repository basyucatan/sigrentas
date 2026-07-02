<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'contratos';

    protected $fillable = ['IdCuarto','IdInquilino','IdPropietario','fechaIni','fechaFin',
        'montoRenta','deposito','penaEntrega','docContrato','docInvMuebles','firma','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
	
    public function cuarto()
    {
        return $this->hasOne('App\Models\Cuarto', 'id', 'IdCuarto');
    }
    
    public function inquilino()
    {
        return $this->hasOne('App\Models\Inquilino', 'id', 'IdInquilino');
    }
    
    public function propietario()
    {
        return $this->hasOne('App\Models\Propietario', 'id', 'IdPropietario');
    }
    
    public function recibos()
    {
        return $this->hasMany('App\Models\Recibo', 'IdContrato', 'id');
    }
    
}
