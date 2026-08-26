<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'=>'Sheikh Md. Rubayet Islam Ifti',
            'email'=>'rubayetislam16@gmail.com',
            'password'=>Hash::make('123456'),
        ]);
    }
}
