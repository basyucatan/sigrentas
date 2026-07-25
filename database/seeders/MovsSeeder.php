<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class MovsSeeder extends Seeder
{
    public function run()
    {
$contratos = array(
  array('id' => '1','IdCuarto' => '1','IdInquilino' => '1','IdPropietario' => '1','fechaIni' => '2026-07-06','fechaFin' => '2027-07-06','montoRenta' => '6500.00','deposito' => '5000.00','penaEntrega' => '500.00','docContrato' => 'xd','docInvMuebles' => 'xd','firma' => 'xd','adicionales' => NULL)
);
DB::table('contratos')->insert($contratos);

$asistencias = array(
  array('id' => '6','IdUser' => '1','fecha' => '2026-06-22','horaEnt' => '08:24:15','horaSal' => '19:05:42','ubiEnt' => '20.980460571980746,-89.62444967262628','ubiSal' => '20.980479002402397,-89.62446854547412','adicionales' => NULL),
  array('id' => '7','IdUser' => '1','fecha' => '2026-06-23','horaEnt' => '08:24:15','horaSal' => '19:05:42','ubiEnt' => '20.980460571980746,-89.62444967262628','ubiSal' => '20.980479002402397,-89.62446854547412','adicionales' => NULL),
  array('id' => '8','IdUser' => '1','fecha' => '2026-06-24','horaEnt' => '08:28:10','horaSal' => '19:02:11','ubiEnt' => '20.980460571980746,-89.62444967262628','ubiSal' => '20.980479002402397,-89.62446854547412','adicionales' => NULL),
  array('id' => '23','IdUser' => '1','fecha' => '2026-06-25','horaEnt' => '08:29:50','horaSal' => '19:03:11','ubiEnt' => '20.980460571980746,-89.62444967262628','ubiSal' => '20.980479002402397,-89.62446854547412','adicionales' => NULL),
  array('id' => '24','IdUser' => '1','fecha' => '2026-06-26','horaEnt' => '08:50:55','horaSal' => '13:01:05','ubiEnt' => '20.980460571980746,-89.62444967262628','ubiSal' => '20.980479002402397,-89.62446854547412','adicionales' => NULL),
  array('id' => '25','IdUser' => '1','fecha' => '2026-06-29','horaEnt' => '08:22:15','horaSal' => '19:04:55','ubiEnt' => '20.980460571980746,-89.62444967262628','ubiSal' => '20.980479002402397,-89.62446854547412','adicionales' => NULL),
  array('id' => '26','IdUser' => '1','fecha' => '2026-06-30','horaEnt' => '08:25:40','horaSal' => '19:02:30','ubiEnt' => '20.980460571980746,-89.62444967262628','ubiSal' => '20.980479002402397,-89.62446854547412','adicionales' => NULL),
  array('id' => '27','IdUser' => '1','fecha' => '2026-06-01','horaEnt' => '08:21:02','horaSal' => '19:05:12','ubiEnt' => '20.980460571980746,-89.62444967262628','ubiSal' => '20.980479002402397,-89.62446854547412','adicionales' => NULL),
  array('id' => '28','IdUser' => '1','fecha' => '2026-07-02','horaEnt' => '08:26:18','horaSal' => '19:01:40','ubiEnt' => '20.980460571980746,-89.62444967262628','ubiSal' => '20.980479002402397,-89.62446854547412','adicionales' => NULL),
  array('id' => '29','IdUser' => '1','fecha' => '2026-07-03','horaEnt' => '09:40:00','horaSal' => '19:00:22','ubiEnt' => '20.941235411122334,-89.61234411223412','ubiSal' => '20.980479002402397,-89.62446854547412','adicionales' => '{"penaEntradaId":1,"justificacionEntrada":"Ubicación lejana por junta externa"}'),
  array('id' => '30','IdUser' => '1','fecha' => '2026-07-06','horaEnt' => '08:54:33','horaSal' => '13:03:15','ubiEnt' => '20.980460571980746,-89.62444967262628','ubiSal' => '20.980479002402397,-89.62446854547412','adicionales' => NULL),
  array('id' => '31','IdUser' => '1','fecha' => '2026-07-07','horaEnt' => '08:23:10','horaSal' => '19:02:44','ubiEnt' => '20.980460571980746,-89.62444967262628','ubiSal' => '20.980479002402397,-89.62446854547412','adicionales' => NULL),
  array('id' => '32','IdUser' => '1','fecha' => '2026-07-08','horaEnt' => '08:25:01','horaSal' => '19:04:18','ubiEnt' => '20.980460571980746,-89.62444967262628','ubiSal' => '20.980479002402397,-89.62446854547412','adicionales' => NULL)
);
DB::table('asistencias')->insert($asistencias);
$gastos = [
    ['IdEfectuo' => 1, 'IdAutorizo' => null, 'estatus' => 'pendiente', 'monto' => null, 'fecha' => Carbon::now()->subDays(2)->format('Y-m-d'), 'foto' => 'gasto-1784561972.png.png', 'adicionales' => json_encode(['concepto' => 'Gasolina para traslado'])],
    ['IdEfectuo' => 1, 'IdAutorizo' => null, 'estatus' => 'efectuado', 'monto' => null, 'fecha' => Carbon::now()->subDays(5)->format('Y-m-d'), 'foto' => '', 'adicionales' => json_encode(['concepto' => 'Cambio de aceite'])],
    ['IdEfectuo' => 1, 'IdAutorizo' => null, 'estatus' => 'autorizado', 'monto' => null, 'fecha' => Carbon::now()->subDays(10)->format('Y-m-d'), 'foto' => '', 'adicionales' => json_encode(['concepto' => 'Comida de trabajo'])],
    ['IdEfectuo' => 1, 'IdAutorizo' => null, 'estatus' => 'cancelado', 'monto' => null, 'fecha' => Carbon::now()->subDays(12)->format('Y-m-d'), 'foto' => '', 'adicionales' => json_encode(['concepto' => 'Hojas y papelería'])],
    ['IdEfectuo' => 1, 'IdAutorizo' => null, 'estatus' => 'pendiente', 'monto' => null, 'fecha' => Carbon::now()->format('Y-m-d'), 'foto' => '', 'adicionales' => json_encode(['concepto' => 'Material de consumo'])]
];
DB::table('gastos')->insert($gastos);
    }
}


