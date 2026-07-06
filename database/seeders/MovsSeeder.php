<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class MovsSeeder extends Seeder
{
    public function run()
    {
$contratos = array(
  array('id' => '1','IdCuarto' => '1','IdInquilino' => '1','IdPropietario' => '1','fechaIni' => '2026-07-06','fechaFin' => '2027-07-06','montoRenta' => '6500.00','deposito' => '5000.00','penaEntrega' => '500.00','docContrato' => 'xd','docInvMuebles' => 'xd','firma' => 'xd','adicionales' => NULL)
);
DB::table('contratos')->insert($contratos);
    }
}


