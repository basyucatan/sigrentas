<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Exception;

class Contrato extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $table = 'contratos';

    protected $fillable = [
        'IdCuarto','IdInquilino','IdPropietario','fechaIni',
        'fechaFin','montoRenta','deposito','penaEntrega','docContrato',
        'docInvMuebles','firma','adicionales'
    ];

    protected $casts = [
        'adicionales' => 'array'
    ];

    protected static function booted()
    {
        static::saving(function ($contrato) {
            $contrato->disponibilidad();
        });
    }

    public function disponibilidad()
    {
        $ocupado = static::where('IdCuarto', $this->IdCuarto)
            ->where('id', '!=', $this->id ?? 0)
            ->where(function ($query) {
                $fIni = $this->fechaIni;
                $fFin = $this->fechaFin;
                $query->where(function ($q) use ($fIni, $fFin) {
                    if ($fFin) {
                        $q->where('fechaIni', '<=', $fFin)
                          ->where(function ($sub) use ($fIni) {
                              $sub->whereNull('fechaFin')
                                  ->orWhere('fechaFin', '>=', $fIni);
                          });
                    } else {
                        $q->whereNull('fechaFin')
                          ->orWhere('fechaFin', '>=', $fIni);
                    }
                });
            })
            ->exists();
        if ($ocupado) {
            throw new Exception('Cuarto ocupado en ese rango de fechas.');
        }
    }

    public function cuarto()
    {
        return $this->belongsTo('App\Models\Cuarto', 'IdCuarto', 'id');
    }
    
    public function inquilino()
    {
        return $this->belongsTo('App\Models\Inquilino', 'IdInquilino', 'id');
    }
    
    public function propietario()
    {
        return $this->belongsTo('App\Models\Propietario', 'IdPropietario', 'id');
    }
    
    public function recibos()
    {
        return $this->hasMany('App\Models\Recibo', 'IdContrato', 'id');
    }
}