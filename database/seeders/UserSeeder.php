<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{    
    public function run()
    {
        User::create(['id'=>1,'name'=>'Basilio','email'=>'basilio_hh@hotmail.com','telefono'=>'9991',
            'password'=>Hash::make('1234'),'activo'=>true,'IdDepto'=>5])->assignRole('SuperAdmin');
        User::create(['id'=>2,'name'=>'User','email'=>'user@gmail.com','telefono'=>'9995',
            'password'=>Hash::make('4321'),'activo'=>true,'IdDepto'=>5])->assignRole('User');
        User::create(['id'=>3,'name'=>'Admin','email'=>'admin@gmail.com','telefono'=>'9991001001',
            'password'=>Hash::make('admin$'),'activo'=>true,'IdDepto'=>5])->assignRole('Director');
        User::create(['id'=>4,'name'=>'Tecnico1','telefono'=>'9991002001',
            'password'=>Hash::make('tecnico1$'),'activo'=>true,'IdDepto'=>5])->assignRole('tecnico');
        User::create(['id'=>5,'name'=>'Tecnico2','telefono'=>'9991002002',
            'password'=>Hash::make('tecnico2$'),'activo'=>true,'IdDepto'=>5])->assignRole('tecnico');
        User::create(['id'=>6,'name'=>'Inquilino1','telefono'=>'9991003001',
            'password'=>Hash::make('inquilino1$'),'activo'=>true,'IdDepto'=>6])->assignRole('inquilino');
        User::create(['id'=>7,'name'=>'Rich','telefono'=>'9991005001',
            'password'=>Hash::make('Rich$'),'activo'=>true,'IdDepto'=>5])->assignRole('inquilino');

        $this->crear(['DonShe', 'LaGuerre', 'Marlene', 'Primo', 'Burgos', 'Sheito'],'Admin',101,9991005002,5);
        $this->crear(['Anibal', 'Mario', 'Luis', 'Fabian', 'Jaciel', 'Yen'],'tecnico',201,9991003002,3);
    }
    private function crear($users, $rol, $IdIni, $telIni, $IdDepto)
    {
        foreach ($users as $indice => $nombre) {
            User::create([
                'id' => $IdIni + $indice,
                'name' => $nombre,
                'telefono' => (string)($telIni + $indice),
                'password' => Hash::make($nombre . '$'),
                'activo' => true,
                'IdDepto' => $IdDepto,
                'adicionales' => ['sueldo' => 5000]
            ])->assignRole($rol);
        }
    }
}