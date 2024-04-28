<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('operations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('id_cliente')->constrained('clientes'); //tag
            $table->string('client_company');
            $table->string('N_declaration');
            $table->date('La_Date');
            $table->string('N_Facture');
            $table->string('Montant');
            $table->string('N_Bill');
            $table->string('N_Repartoire');
            $table->string('telecharger_fisher');
            $table->softDeletes(); // Adds deleted_at column
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations');
    }
};
