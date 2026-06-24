<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatsBasicosSeeder extends Seeder
{
    public function run()
    {
$casas = array(
  array('id' => '1','casa' => 'cubanos','direccion' => 'Calle 29, 97302 Dzityá, Yuc.','gmaps' => 'https://maps.app.goo.gl/uCVV78432xGVjbhH6','adicionales' => '{"noCuartos":"10","coordenadas":"21.0253964,-89.6678628","foto":"casas\\/casa-5.png"}'),
  array('id' => '2','casa' => 'chuburna','direccion' => 'C. 23ᴬ 99-I, Chuburna de Hidalgo, 97205 Mérida, Yuc.','gmaps' => 'https://maps.app.goo.gl/LB9D993DBpXGgu8U9','adicionales' => '{"noCuartos":"6","coordenadas":null,"foto":"casas\\/casa-6.png"}')
);
DB::table('casas')->insert($casas);
$cuartos = array(
  array('id' => '1','IdCasa' => '2','cuarto' => '1','estatus' => 'disponible','adicionales' => NULL),
  array('id' => '2','IdCasa' => '2','cuarto' => '2','estatus' => 'disponible','adicionales' => NULL),
  array('id' => '3','IdCasa' => '2','cuarto' => '3','estatus' => 'disponible','adicionales' => NULL),
  array('id' => '4','IdCasa' => '2','cuarto' => '4','estatus' => 'disponible','adicionales' => NULL),
  array('id' => '5','IdCasa' => '2','cuarto' => '5','estatus' => 'disponible','adicionales' => NULL),
  array('id' => '6','IdCasa' => '2','cuarto' => '6','estatus' => 'disponible','adicionales' => NULL),
  array('id' => '7','IdCasa' => '1','cuarto' => '1','estatus' => 'disponible','adicionales' => NULL),
  array('id' => '8','IdCasa' => '1','cuarto' => '2','estatus' => 'disponible','adicionales' => NULL),
  array('id' => '9','IdCasa' => '1','cuarto' => '3','estatus' => 'disponible','adicionales' => NULL),
  array('id' => '10','IdCasa' => '1','cuarto' => '4','estatus' => 'disponible','adicionales' => NULL),
  array('id' => '11','IdCasa' => '1','cuarto' => '5','estatus' => 'disponible','adicionales' => NULL),
  array('id' => '12','IdCasa' => '1','cuarto' => '6','estatus' => 'disponible','adicionales' => NULL),
  array('id' => '13','IdCasa' => '1','cuarto' => '7','estatus' => 'disponible','adicionales' => NULL),
  array('id' => '14','IdCasa' => '1','cuarto' => '8','estatus' => 'disponible','adicionales' => NULL),
  array('id' => '15','IdCasa' => '1','cuarto' => '9','estatus' => 'disponible','adicionales' => NULL),
  array('id' => '16','IdCasa' => '1','cuarto' => '10','estatus' => 'disponible','adicionales' => NULL)
);
DB::table('cuartos')->insert($cuartos);
$vehiculos = array(
  array('id' => '1','vehiculo' => 'CARRO MOTO','numero' => '1','estatus' => 'disponible','adicionales' => NULL),
  array('id' => '2','vehiculo' => 'CARRO MOTO','numero' => '2','estatus' => 'disponible','adicionales' => NULL)
);
DB::table('vehiculos')->insert($vehiculos);
$tecnicos = array(
  array('id' => '1','IdUser' => '4','IdVehiculo' => '1','tecnico' => 'JUAN','telefono' => '999','activo' => '1','adicionales' => NULL),
  array('id' => '2','IdUser' => '5','IdVehiculo' => '2','tecnico' => 'PEDRO','telefono' => '9992','activo' => '1','adicionales' => NULL)
);
DB::table('tecnicos')->insert($tecnicos);
$fallas = array(
  array('id' => '1','falla' => 'Electricidad'),
  array('id' => '2','falla' => 'Plomeria'),
  array('id' => '3','falla' => 'Albañilería'),
  array('id' => '4','falla' => 'Carpintería'),
  array('id' => '5','falla' => 'Otro'),
  array('id' => '6','falla' => 'General')
);
DB::table('fallas')->insert($fallas);
$prioridads = array(
  array('id' => '1','prioridad' => 'Urgente','diasTolerancia' => '1','colorHex' => 'FF0000'),
  array('id' => '2','prioridad' => 'Entrega','diasTolerancia' => '1','colorHex' => 'FF2900'),
  array('id' => '3','prioridad' => 'Alta','diasTolerancia' => '1','colorHex' => 'FF9D00'),
  array('id' => '4','prioridad' => 'Media','diasTolerancia' => '3','colorHex' => 'FFE600'),
  array('id' => '5','prioridad' => 'Baja','diasTolerancia' => '6','colorHex' => 'D0FF00')
);
DB::table('prioridads')->insert($prioridads);
    }
}
