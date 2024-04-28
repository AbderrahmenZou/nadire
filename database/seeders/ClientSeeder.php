<?php

namespace Database\Seeders;

use App\Models\cliente;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        cliente::create([
            'nom_et_prenom' => fake()->name,
            'N_Registre' => fake()->randomNumber(8),
            'client_company' => fake()->company,
            'nif' => '123456',
            'nis' => 123456,
            'telecharger_fisher' => 'telecharger_fisher',
        ]);
    }
}
