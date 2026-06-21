<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'tickets';

    protected $fillable = ['IdCuarto','IdFalla','IdAutor','IdTecnico','tipo','estatus','ticket','fechaSol','fechaFin','adicionales'];
    protected $casts = [
        'adicionales' => 'array'
    ];
	
    public function cuarto()
    {
        return $this->hasOne('App\Models\Cuarto', 'id', 'IdCuarto');
    }
    
    public function evidencias()
    {
        return $this->hasMany('App\Models\Evidencia', 'IdTicket', 'id');
    }
    
    public function falla()
    {
        return $this->hasOne('App\Models\Falla', 'id', 'IdFalla');
    }
    
    public function tecnico()
    {
        return $this->hasOne('App\Models\Tecnico', 'id', 'IdTecnico');
    }
    
    public function ticketssegs()
    {
        return $this->hasMany('App\Models\Ticketsseg', 'IdTicket', 'id');
    }
    
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'IdAutor');
    }
    
}
