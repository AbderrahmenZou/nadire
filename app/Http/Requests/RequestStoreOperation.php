<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestStoreOperation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
        'id_cliente' => 'required',
        'client_company' => 'required',
        'N_declaration' => 'required',
        'La_Date' => 'required',
        'N_Facture' => 'required',
        'Montant' => 'required',
        'N_Bill' => 'required',
        'N_Repartoire' => 'required',
        'telecharger_fisher' => 'required',
        ];
    }
}
