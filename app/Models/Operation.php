<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class operation extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'id_cliente',
        'client_company',
        'N_declaration',
        'La_Date',
        'N_Facture',
        'Montant',
        'N_Bill',
        'N_Repartoire',
        'telecharger_fisher',

    ];

    
    // في نموذج العملية (Operation model)
    public function cliente() {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

}
