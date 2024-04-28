<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Document;
use App\Models\Company;
use App\Models\DocumentCompany;

class DocumentComapny extends Model
{
    use HasFactory;
    protected $fillable = [
        'document_company',
    ];
}
