<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Cliente;
use App\Models\OperationDetail;


class Operation extends Model
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

    // Define the relationship with Cliente model
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }
}
