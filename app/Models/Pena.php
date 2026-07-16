<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pena extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'penas';

    protected $fillable = ['pena','descuentoDias'];
    
	
}
