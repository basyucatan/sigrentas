<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{    
    public function run()
    {
        User::create(['id'=>1,'name'=>'Basilio','email'=>'basilio_hh@hotmail.com','telefono'=>'9991',
            'password'=>Hash::make('1234'),'activo'=>true,'IdDepto'=>9])->assignRole('SuperAdmin');
        User::create(['id'=>2,'name'=>'User','email'=>'user@gmail.com','telefono'=>'9995',
            'password'=>Hash::make('4321'),'activo'=>true,'IdDepto'=>9])->assignRole('User');
        User::create(['id'=>3,'name'=>'Admin','email'=>'admin@gmail.com','telefono'=>'9991001001',
            'password'=>Hash::make('admin$'),'activo'=>true,'IdDepto'=>9])->assignRole('Director');
        User::create(['id'=>4,'name'=>'Tecnico1','telefono'=>'9991002001',
            'password'=>Hash::make('tecnico1$'),'activo'=>true,'IdDepto'=>9])->assignRole('Admin');
        User::create(['id'=>5,'name'=>'Tecnico2','telefono'=>'9991002002',
            'password'=>Hash::make('tecnico2$'),'activo'=>true,'IdDepto'=>9])->assignRole('Admin');
    }
}
