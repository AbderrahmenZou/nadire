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
            'telecharger_fisher' => 'required|file', // Ensure it is a file
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

    // باقي الطرق الأخرى بدون تعديلات



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
    public function edit($id)
    {
        $clientes = cliente::all();
        $operation = operation::findorfail($id);
        return view('operations.edit', compact('operation', 'clientes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $operation = operation::findorfail($id);
        $operation->update([
            'id_cliente' => $request->id_cliente,
            'client_company' => $request->client_company,
            'N_declaration' => $request->N_declaration,
            'La_Date' => $request->La_Date,
            'N_Facture' => $request->N_Facture,
            'Montant' => $request->Montant,
            'N_Bill' => $request->N_Bill,
            'N_Repartoire' => $request->N_Repartoire,
        ]);
        if ($request->hasFile('telecharger_fisher')) {
            if (Storage::disk('doc_operation')->exists($operation->telecharger_fisher)) {
                Storage::disk('doc_operation')->delete($operation->telecharger_fisher);
            }
            $document = $request->file('telecharger_fisher')->getClientOriginalName();
            $path = $request->file('telecharger_fisher')->storeAs('operation', $document, 'doc_operation');
            $operation->update([
                'telecharger_fisher' => $path,
            ]);
        }
        return redirect()->Route('operations.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        operation::destroy($id);

        return redirect()->Route('operations.index');
    }

    
    public function getClient(Request $request)
    {


        if ($search = $request->id or $request->name) {

            $name_or_id_cliente = cliente::where('id', 'LIKE', "%$search%")
                ->orwhere('nom_et_prenom', 'LIKE', "%$search%")
                ->get();
        }
        return response()->json($name_or_id_cliente);
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
        })
            ->orwhereHas('cliente', function ($query) use ($search) {
                $query->where('nom_et_prenom', 'like', "%$search%")
                    ->orwhere('N_Registre',  'like', "%$search%")
                    ->orwhere('nif',  'like', "%$search%")
                    ->orwhere('nis',  'like', "%$search%")
                    ->orwhere('telecharger_fisher',  'like', "%$search%");
            })
            ->get();

        return view('operations.index', compact('operations', 'search'));
    }


    public function downloadOperation(Request $request, $id)
    {
        $file = operation::find($id);
        // $file->document_company;
        return response()->download(public_path('documents/' . $file->telecharger_fisher));
    }
}
