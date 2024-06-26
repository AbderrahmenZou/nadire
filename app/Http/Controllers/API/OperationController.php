<?php

namespace App\Http\Controllers\API;

use App\Models\Operation;
use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;


class OperationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // استرداد كافة العمليات مع بيانات العملاء المرتبطين بها
        $operations = Operation::all();

        // التحقق مما إذا كانت هناك بيانات عمليات متاحة
        if ($operations->isEmpty()) {
            return response()->json(['message' => 'No operations found'], 404);
        }

        // Return JSON response with operations data
        return response()->json(['message' => 'Operations retrieved successfully', 'data' => $operations], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Store a newly created resource in storage.
     */
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data



        $request->validate([
            'id_cliente' => 'required',
            'client_company' => 'required',
            'N_declaration' => 'required',
            'La_Date' => 'required',
            'N_Facture' => 'required',
            'Montant' => 'required',
            'N_Bill' => 'required',
            'N_Repartoire' => 'required',
            'telecharger_fisher' => 'required',
        ]);
        // Determine the original file name
        $document = $request->file('telecharger_fisher')->getClientOriginalName();




        // Attempt to store the file
        try {
            $path = $request->file('telecharger_fisher')->storeAs('operation', $document, 'doc_operation');
        } catch (\Exception $e) {
            // In case storing the file fails
            return response()->json(['error' => 'An error occurred while uploading the file'], 500);
        }


        // Create a new Operation resource
        $operation = Operation::create([
            'id_cliente' => $request->id_cliente,
            'client_company' => $request->client_company,
            'N_declaration' => $request->N_declaration,
            'La_Date' => $request->La_Date,
            'N_Facture' => $request->N_Facture,
            'Montant' => $request->Montant,
            'N_Bill' => $request->N_Bill,
            'N_Repartoire' => $request->N_Repartoire,
            'telecharger_fisher' => $path,

        ]);

        // dd($request->id_cliente);
        // Check if the operation was created successfully
        if ($operation) {
            // Return a success response with the created operation data
            return response()->json(['message' => 'Operation created successfully', 'operation' => $operation], 201);
        } else {
            // Return an error response if creating the operation fails
            return response()->json(['error' => 'Failed to create the operation'], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $operation = operation::findorfail($id);

        if (!$operation) {
            // Return a not found response if Cliente does not exist
            return response()->json(['error' => 'operation not found'], 404);
        } else {
            return response()->json(['message' => 'operation exists', 'operation' => $operation], 200);
        }
        // return view('operations.show', compact('operation'));
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit($id)
    {
        $clientes = cliente::all();
        $operation = operation::findorfail($id);

        if (!$operation) {
            // Return a not found response if Cliente does not exist
            return response()->json(['error' => 'operation not found'], 404);
        }

        if (!$clientes) {
            // Return a not found response if Cliente does not exist
            return response()->json(['error' => 'Clientes not found'], 404);
        }
        // return view('operations.edit', compact('operation', 'clientes'));
        return response()->json(['cliente' => $clientes, 'operation' => $operation]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            // Find the operation by ID
            $operation = operation::findOrFail($request->id);

            if (!$operation) {
                // Return a not found response if Cliente does not exist
                return response()->json(['error' => 'operation not found'], 404);
            }
            $operation = operation::where("id", $request->id)->update([
                'id_cliente' => $request->id_cliente,
                'client_company' => $request->client_company,
                'N_declaration' => $request->N_declaration,
                'La_Date' => $request->La_Date,
                'N_Facture' => $request->N_Facture,
                'Montant' => $request->Montant,
                'N_Bill' => $request->N_Bill,
                'N_Repartoire' => $request->N_Repartoire,
            ]);

            // Update the operation's attributes
            // $operation->id_cliente = $request->id_cliente;
            // $operation->client_company = $request->client_company;
            // $operation->N_declaration = $request->N_declaration;
            // $operation->La_Date = $request->La_Date;
            // $operation->N_Facture = $request->N_Facture;
            // $operation->Montant = $request->Montant;
            // $operation->N_Bill = $request->N_Bill;
            // $operation->N_Repartoire = $request->N_Repartoire;


            // Check if a new file is uploaded
            if ($request->hasFile('telecharger_fisher')) {
                // Delete the previous file if it exists
                if (Storage::disk('doc_operation')->exists($operation->telecharger_fisher)) {
                    Storage::disk('doc_operation')->delete($operation->telecharger_fisher);
                }

                // Store the new file
                $document = $request->file('telecharger_fisher')->getClientOriginalName();
                $path = $request->file('telecharger_fisher')->storeAs('operation', $document, 'doc_operation');
                $operation->telecharger_fisher = $path;
            }

            // Save the operation
            $operation->save();

            return response()->json(['msg' => 'Operation updated successfully', 'operation' => $operation]);
        } catch (\Exception $e) {
            return response()->json(['msg' => 'Error updating operation'], 500);
        }
    }





    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        try {
            // Find the operation by ID
            $operation = operation::findOrFail($request->id);

            // Delete the operation
            $operation->delete();

            // Return a success response
            return response()->json(['message' => 'Operation deleted successfully', 'operation' => $operation]);
        } catch (\Exception $e) {
            // Return an error response if the operation does not exist or if there is any other error
            return response()->json(['error' => 'Failed to delete operation'], 500);
        }
    }



    public function searchOperations(Request $request)
    {
        $search = $request->search;
        $operations = operation::where(function ($query) use ($search) {
            $query->where('id_cliente',  'like', "%$search%")
                ->orwhere('client_company', 'like', "%$search%")
                ->orwhere('N_declaration',  'like', "%$search%")
                ->orwhere('N_Facture',  'like', "%$search%")
                ->orwhere('N_Bill',  'like', "%$search%")
                ->orwhere('N_Repartoire',  'like', "%$search%")
                ->orwhere('Montant',  'like', "%$search%");
        })->get();

        if ($operations->isEmpty()) {
            // إرجاع رسالة تعليمية في حالة عدم وجود نتائج
            return response()->json(['message' => 'No results found for the specified search criteria']);
        }

        // إرجاع النتائج كـ JSON
        return response()->json(['operations' => $operations]);
    }


    public function downloadOperation(Request $request)
    {
        // Find the client record by ID
        $id = $request->id;
        $operation = operation::find($id);

        // Check if the client record exists
        if (!$operation) {
            return response()->json(['msg' => 'Client not found'], 404);
        }

        // // Get the file path from the client record
        $filePath = $operation->telecharger_fisher;

        // Check if the file exists
        if (!Storage::disk('doc_operation')->exists($filePath)) {
            return response()->json(['msg' => 'File not found'], 404);
        }

        // Download the file
        return response()->download(public_path('documents/' . $filePath));
        // dd($filePath);
    }
}
