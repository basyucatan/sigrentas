<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquilino extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'inquilinos';

    protected $fillable = ['IdUser','inquilino','telefono','activo','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
	
    public function contratos()
    {
        return $this->hasMany('App\Models\Contrato', 'IdInquilino', 'id');
    }
    
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'IdUser');
    }
    
}
