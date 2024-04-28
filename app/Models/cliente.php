<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class cliente extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'id_client',
        'nom_et_prenom',
        'N_Registre',
        'client_company',
        'nif',
        'nis',
        'telecharger_fisher',
    ];

    public function operations()
    {
        return $this->hasMany(operation::class);
    }
    public function document()
    {
        return $this->hasMany(DocumentCliente::class);
    }
}
