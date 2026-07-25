<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'gastos';

    protected $fillable = ['IdEfectuo','IdAutorizo','estatus','monto','fecha','foto','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
	
    public function Autorizo()
    {
        return $this->hasOne('App\Models\User', 'id', 'IdAutorizo');
    }
    
    public function Efectuo()
    {
        return $this->hasOne('App\Models\User', 'id', 'IdEfectuo');
    }
    
}
