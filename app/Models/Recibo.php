<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recibo extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'recibos';

    protected $fillable = ['IdContrato','montoRenta','fechaVence','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
	
    public function contrato()
    {
        return $this->hasOne('App\Models\Contrato', 'id', 'IdContrato');
    }
    
    public function pagos()
    {
        return $this->hasMany('App\Models\Pago', 'IdRecibo', 'id');
    }
    
}
