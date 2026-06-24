<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Falla extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'fallas';

    protected $fillable = ['falla'];
    
	
    public function tickets()
    {
        return $this->hasMany('App\Models\Ticket', 'IdFalla', 'id');
    }
    
}
