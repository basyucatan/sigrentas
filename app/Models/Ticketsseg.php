<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticketsseg extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'ticketssegs';

    protected $fillable = ['IdTicket','IdUser','comentario'];
    
	
    public function ticket()
    {
        return $this->hasOne('App\Models\Ticket', 'id', 'IdTicket');
    }
    
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'IdUser');
    }
    
}
