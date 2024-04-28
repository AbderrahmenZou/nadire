<?php

namespace Database\Seeders;

use App\Models\cliente;
use App\Models\operation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OperationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cliente = cliente::all();
        for ($i = 0; $i < 5; $i++) {
            operation::create([
                'id_cliente' => $cliente->random()->id,
                'client_company' => $cliente->random()->client_company,
                'N_declaration' => random_int(1000, 9999),
                'La_Date' => now(),
                'N_Facture' => 'factur4544',
                'Montant' => rand(1000, 9999),
                'N_Bill' => 'vbjgdfd',
                'N_Repartoire' => 'vbjgdfd',
                'telecharger_fisher' => $cliente->random()->telecharger_fisher,
            ]);
        }
    }
}
