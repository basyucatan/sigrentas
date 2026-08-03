<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{    
    public function run()
    {
        $this->crear(['Basilio'],'SuperAdmin',1,9991,1);
        $this->crear(['Rich'],'director',2,9991005001,1);
        $this->crear(['DonShe', 'LaGuerre', 'Marlene', 'Primo', 'Burgos', 'Sheito'],'Admin',101,9991005002,5);
        $this->crear(['Anibal', 'Mario', 'Luis', 'Fabian', 'Jaciel', 'Yen'],'tecnico',201,9991003002,3);
        $this->crear(['Inquilino1'],'inquilino',501,9991004001,6);
        $this->asignarSueldos();
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
    private function asignarSueldos()
    {
        User::each(function ($user) {
            $adicionales = $user->adicionales ?? [];
            $adicionales['sueldo'] = 5000;
            $user->update([
                'adicionales' => $adicionales
            ]);
        });
    }
}