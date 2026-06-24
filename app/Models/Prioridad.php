<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prioridad extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'prioridads';

    protected $fillable = ['prioridad','diasTolerancia','colorHex'];
    
	
    public function tickets()
    {
        return $this->hasMany('App\Models\Ticket', 'IdPrioridad', 'id');
    }
    
}
