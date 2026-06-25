<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Ticket extends Model
{
	use HasFactory;
	
    public $timestamps = false;

    protected $table = 'tickets';

    protected $fillable = ['IdCuarto','IdFalla','IdAutor','IdTecnico','IdPrioridad','tipo','estatus',
        'ticket','fechaSol','fechaFin','adicionales'];
    protected $casts = ['adicionales' => 'array'];
public function getAniejaAttribute()
{
    $inicio = Carbon::parse($this->fechaSol, 'America/Mexico_City');
    $ahora = now('America/Mexico_City');
    $totalHoras = $inicio->diffInHours($ahora);
    $dias = intdiv($totalHoras, 24);
    $horas = $totalHoras % 24;
    $diasTolerancia = $this->prioridad->diasTolerancia ?? 0;
    $colorHex = $this->prioridad->colorHex ?? '6C757D';
    $fechaLimite = $inicio->copy()->addDays($diasTolerancia);
    $colorEstado = $ahora->gt($fechaLimite) ? 'danger' : 'success';
    $r = hexdec(substr($colorHex, 0, 2));
    $g = hexdec(substr($colorHex, 2, 2));
    $b = hexdec(substr($colorHex, 4, 2));
    $luminancia = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
    return [
        'texto' => $dias . 'D ' . $horas . 'H',
        'color' => $colorEstado,
        'dias' => $dias,
        'horas' => $horas,
        'colorPrioridadFondo' => '#' . $colorHex,
        'colorPrioridadTexto' => $luminancia > 128 ? '#000000' : '#FFFFFF'
    ];
}

    public function cuarto(){return $this->hasOne('App\Models\Cuarto', 'id', 'IdCuarto');}
    public function evidencias(){return $this->hasMany('App\Models\Evidencia', 'IdTicket', 'id');}
    public function falla(){return $this->hasOne('App\Models\Falla', 'id', 'IdFalla');}
    public function prioridad(){return $this->hasOne('App\Models\Prioridad', 'id', 'IdPrioridad');}
    public function tecnico(){return $this->hasOne('App\Models\Tecnico', 'id', 'IdTecnico');}
    public function ticketssegs(){return $this->hasMany('App\Models\Ticketsseg', 'IdTicket', 'id');}
    public function user(){return $this->hasOne('App\Models\User', 'id', 'IdAutor');}
    
}
