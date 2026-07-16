<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'asistencias';

    protected $fillable = ['IdUser','fecha','horaEnt','horaSal','ubiEnt','ubiSal','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
	
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'IdUser');
    }
    
}
