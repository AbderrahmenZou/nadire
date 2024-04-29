<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\cliente;
use App\Models\Operation;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClienteRequest;
use App\Http\Requests\OperationRequest;
use App\Http\Requests\DocumentClienteRequest;
use App\Http\Requests\DocumentCompanyRequest;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use App\Http\Controllers\Api\JWT;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\EnsureTokenIsValid;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Retrieve all clientes from the database
        $clientes = Cliente::all();

        // $token = JWTAuth::parseToken()->getToken();

        // // استخراج الرمز (token) من الطلب وتخزينه في متغير
        // return response()->json(['msg' => $token]);
        // // Check if any clientes were found

        // if(!$token){
        //     return response()->json(['message' => 'il na pas login'], 404);
        // }elseif ($clientes->isEmpty()) {
        //     return response()->json(['message' => 'No clientes found'], 404);
        // }

        // Return JSON response with clientes data
        return response()->json(['message' => 'Clientes retrieved successfully', 'data' => $clientes], 200);
    }


    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'nom_et_prenom' => 'required',
            'N_Registre' => 'required',
            'client_company' => 'required',
            'nif' => 'required',
            'nis' => 'required',
            'telecharger_fisher' => 'required|file', // Ensure it is a file
        ]);


        // Determine the original file name
        $documentName = $request->file('telecharger_fisher')->getClientOriginalName();

        // Attempt to store the file
        try {
            $path = $request->file('telecharger_fisher')->storeAs('client', $documentName, 'doc_client');
        } catch (\Exception $e) {
            // In case storing the file fails
            return response()->json(['error' => 'An error occurred while uploading the file'], 500);
        }

        // Create a new Cliente resource
        $cliente = Cliente::create([
            'nom_et_prenom' => $validatedData['nom_et_prenom'],
            'N_Registre' => $validatedData['N_Registre'],
            'client_company' => $validatedData['client_company'],
            'nif' => $validatedData['nif'],
            'nis' => $validatedData['nis'],
            'telecharger_fisher' => $path, // Store the uploaded file path
        ]);

        // Check if the cliente was created successfully
        if ($cliente) {
            // Return a success response with the created cliente data
            return response()->json(['message' => 'Cliente created successfully', 'cliente' => $cliente], 201);
        } else {
            // Return an error response if creating the cliente fails
            return response()->json(['error' => 'Failed to create the cliente'], 500);
        }
    }



    /*  Storage::disk('client')->put('text.txt', $request->document_company);

         $formfileds['telecharger_fisher'] = $request->file('telecharger_fisher')->store('telecharger_fisher', 'public');
         return reponse('donne telechargement done');
         return redirect()->Route('clientes.index');}
     */

    /**
     * Display the specified resource.
     */


    public function show($id)
    {

        // Find the Cliente by ID
        $cliente = Cliente::find($id);

        // Check if the Cliente exists
        if (!$cliente) {
            // Return a not found response if Cliente does not exist
            return response()->json(['error' => 'Cliente not found'], 404);
        }

        // Retrieve operations related to the Cliente
        $operations = Operation::where('id_cliente', $id)->get();

        // Return the Cliente data along with related operations
        return response()->json(['cliente' => $cliente, 'operations' => $operations]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        // Find the Cliente by ID
        $cliente = Cliente::find($id);

        // Check if the Cliente exists
        if (!$cliente) {
            // Return a not found response if Cliente does not exist
            return response()->json(['error' => 'Cliente not found'], 404);
        }

        // Return the Cliente data for editing
        return response()->json(['cliente' => $cliente]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Find the cliente by ID

        // Update the cliente's attributes
        $updated = cliente::where("id", $request->id)->update([
            'nom_et_prenom' => $request->nom_et_prenom,
            'N_Registre' => $request->N_Registre,
            'client_company' => $request->client_company,
            'nif' => $request->nif,
            'nis' => $request->nis,
            'telecharger_fisher' => $request->telecharger_fisher,
        ]);

        $cliente = cliente::find($request->id);
        // Check if the cliente was updated successfully
        if ($updated) {
            // Check if a new file is uploaded
            if ($request->hasFile('telecharger_fisher')) {
                // Delete the previous file if it exists
                if (Storage::disk('doc_client')->exists($cliente->telecharger_fisher)) {
                    Storage::disk('doc_client')->delete($cliente->telecharger_fisher);
                }

                // Store the new file
                $document = $request->file('telecharger_fisher')->getClientOriginalName();
                $path = $request->file('telecharger_fisher')->storeAs('client', $document, 'doc_client');
                $cliente->telecharger_fisher = $path;
            }
            $cliente->save();
            return response()->json(['msg' => 'Cliente updated successfully', 'cliente' => $cliente]);
        }
        return response()->json(['msg' => "error"]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        // Find the Cliente by ID
        $cliente = cliente::find($request->id);

        // Check if the Cliente exists
        if (!$cliente) {
            // Return a not found response if Cliente does not exist
            return response()->json(['error' => 'Cliente not found'], 404);
        }

        // Delete the Cliente
        $cliente->delete();

        // Return a success response
        return response()->json(['message' => 'Cliente deleted successfully', 'cliente' => $cliente]);
    }




    public function searchCliente(Request $request)
    {
        // استخراج معايير البحث من الطلب
        $search = $request->search;

        // الاستعلام عن العملاء بناءً على المعايير المحددة
        $clientes = Cliente::where(function ($query) use ($search) {
            $query->where('nom_et_prenom', 'like', "%$search%")
                ->orWhere('N_Registre', 'like', "%$search%")
                ->orWhere('nif', 'like', "%$search%")
                ->orWhere('nis', 'like', "%$search%")
                ->orWhere('telecharger_fisher', 'like', "%$search%");
        })->get();

        // التحقق مما إذا كان هناك نتائج
        if ($clientes->isEmpty()) {
            // إرجاع رسالة تعليمية في حالة عدم وجود نتائج
            return response()->json(['message' => 'No results found for the specified search criteria']);
        }

        // إرجاع النتائج كـ JSON
        return response()->json(['clientes' => $clientes]);
    }




    public function downloadClient(Request $request)
    {
        // Find the client record by ID
        $id = $request->id;
        $cliente = Cliente::find($id);



        // Check if the client record exists
        if (!$cliente) {
            return response()->json(['msg' => 'Client not found'], 404);
        }

        // // Get the file path from the client record
        $filePath = $cliente->telecharger_fisher;

        // Check if the file exists
        if (!Storage::disk('doc_client')->exists($filePath)) {
            return response()->json(['msg' => 'File not found'], 404);
        }

        // Download the file
        return response()->download(public_path('documents/' . $filePath));
        // dd($filePath);
    }
}
