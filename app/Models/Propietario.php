<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Propietario extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'propietarios';

    protected $fillable = ['propietario','generales','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
	
    public function contratos()
    {
        return $this->hasMany('App\Models\Contrato', 'IdPropietario', 'id');
    }
    
}
