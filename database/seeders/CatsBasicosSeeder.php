<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CatsBasicosSeeder extends Seeder
{
    public function run()
    {
$casas = array(
  array('id' => '1','casa' => 'cubanos','direccion' => 'Calle 29, 97302 Dzityá, Yuc.','gmaps' => 'https://maps.app.goo.gl/uCVV78432xGVjbhH6','adicionales' => '{"noCuartos":"10","coordenadas":"21.0253964,-89.6678628","foto":"casas\\/casa-5.png","descripcion":"xd"}'),
  array('id' => '2','casa' => 'Madero','direccion' => 'C. 31 188A, Francisco I. Madero, 97240 Mérida, Yuc.','gmaps' => 'https://maps.app.goo.gl/EWmqD4CEDEvZmyN29','adicionales' => '{"noCuartos":"6","coordenadas":null,"foto":"casas\\/casa-6.png","descripcion":"PREDIO URBANO UBICADO EN LA SECCI\\u00d3N CATASTRAL DOCE, MANZANA CIENTO SETENTA Y SEIS DE LA LOCALIDAD Y MUNICIPIO DE M\\u00c9RIDA, ESTADO DE YUCAT\\u00c1N, MARCADO CON EL N\\u00daMERO QUINIENTOS DIECINUEVE DE LA CALLE SESENTA Y CINCO LETRA B, CON LA EXTENSION DE DIEZ METROS DE FRENTE POR VEINTIOCHO METROS OCHENTA CENTIMETROS DE FONDO, DE FORMA IRREGULAR, CUYO PERIMETRO SE DESCRIBE CON LAS SIGUIENTES MEDIDAS PARCIALES: PARTIENDO DEL VERTICE DEL ANGULO SESENTA DEL PREDIO Y DIRIGIENDOSE HACIA EL NOROESTE, MIDE VEINTIOCHO METROS OCHENTA CENTIMETROS; DE ESTE PUNTO HACIA EL NORESTE, MIDE VEINTE METROS; DE AQUI HACIA EL SURESTE, MIDE VEINTITRES METROS SESENTA CENTIMETROS SOBRE LA CALLE CIEN; DE AQUI HACIA EL SUR, CON DIRECCION HACIA EL ORIENTE, MIDE FORMANDO CHAFLAN CUATRO METROS DIEZ CENTIMETROS; Y DE ESTE PUNTO HACIA EL SUR, CON DESVIACI\\u00d3N HACIA EL PONIENTE, HASTA LLEGAR EL PUNTO DE PARTIDA Y CERRAR EL PERIMETRO, SOBRE LA CALLE SESENTA Y CINCO B, QUE ES EL FRENTE MIDE DIEZ METROS; CON UNA SUPERFICIE DE CUATROCIENTOS TREINTA METROS CUADRADOS, Y CON LOS SIGUIENTES LINDEROS: AL NOROESTE, EL PREDIO NUMERO QUINIENTOS CUARENTA Y NUEVE DE LA CALLE CIEN; PARTE EN PARTE EL PREDIO NUMERO QUINIENTOS DIECINUEVE LETRA A, DE LA CALLE SESENTA Y CINCO B; QUE SE DESCRIBIRA Y DESLINDARA INMEDIATAMENTE A CONTINUACION: AL NORESTE, LA CALLE CIEN; AL SURESTE, LA CALLE SESENTA Y CINCO LETRA B; Y AL SUROESTE, EL PREDIO NUMERO QUINIENTOS DIECINUEVE LETRA A DE LA CALLE SESENTA Y CINCO B."}')
);
DB::table('casas')->insert($casas);
$propietarios = array(
  array('id' => '1','propietario' => 'ILEANA VALERIA RAVELL CANUL','generales' => 'haber nacido en esta Ciudad y Municipio de Mérida, Yucatán, el dieciocho de enero del año mil novecientos setenta y tres, ser de cincuenta y tres años de edad, Empleada, Soltera y con domicilio marcado con el predio número trescientos treinta “A” de la calle veintidós letra “B” del Fraccionamiento Bugambilias de esta ciudad y municipio de Mérida, Yucatán y con Clave Única de Registro de Población RACI730118MYNVNL00.','adicionales' => NULL)
);
DB::table('propietarios')->insert($propietarios);
$inquilinos = array(
  array('id' => '1','IdUser' => '6','inquilino' => 'DAYRINE PONCE MARTÍNEZ','telefono' => '999','generales' => 'haber nacido en la Ciudad de Matanzas, Cuba, el diecinueve de septiembre del año mil novecientos noventa y cinco, ser de treinta años de edad , con domicilio en el departamento objeto del presente convenio y con numero de pasaporte K744557, expedido por la República de Cuba.','adicionales' => NULL)
);
DB::table('inquilinos')->insert($inquilinos);
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
  array('id' => '3','falla' => 'Otro'),
  array('id' => '4','falla' => 'General')
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
