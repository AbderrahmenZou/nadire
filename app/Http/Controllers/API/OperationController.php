<?php

namespace App\Http\Controllers\API;
use App\Models\Operation; // تم استيراد الكلاس Operation بشكل صحيح
use Illuminate\Http\Request;
use App\Models\Cliente; // تم تصحيح اسم الكلاس ليتطابق مع النمط بداية الحرف الصغير
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
        // 'id_cliente' => 'required',
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
    $documentName = $request->file('telecharger_fisher')->getClientOriginalName();

    
    // Attempt to store the file
    try {
        $path = $request->file('telecharger_fisher')->storeAs('operation', $documentName, 'doc_operation');
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
        'telecharger_fisher' => $path, // Store the uploaded file path
    ]);

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
        return view('operations.show', compact('operation'));
    }

    /**
     * Show the form for editing the specified resource.
     */

    // public function edit($id)
    // {
    //     $clientes = cliente::all();
    //     $operation = operation::findorfail($id);
    //     return view('operations.edit', compact('operation', 'clientes'));
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            // Find the operation by ID
            $operation = operation::findOrFail($request->id);

            // Update the operation's attributes
            $operation->id_cliente = $request->id_cliente;
            $operation->client_company = $request->client_company;
            $operation->N_declaration = $request->N_declaration;
            $operation->La_Date = $request->La_Date;
            $operation->N_Facture = $request->N_Facture;
            $operation->Montant = $request->Montant;
            $operation->N_Bill = $request->N_Bill;
            $operation->N_Repartoire = $request->N_Repartoire;

            
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

            return response()->json(['msg' => 'Operation updated successfully']);
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
        $id = $request->id;
        $operation = operation::find($id);

        // Check if the client record exists
        if (!$operation) {
            return response()->json(['msg' => 'operation file not found'], 404);
        }

        // Get the file path from the client record
        $filePath = $operation->telecharger_fisher;

        // Check if the file exists
        if (!Storage::disk('doc_operation')->exists($filePath)) {
            return response()->json(['msg' => 'File not found'], 404);
        }

        // Download the file
        return response()->download(storage_path('app/doc_operation/' , $filePath));

    }
}
