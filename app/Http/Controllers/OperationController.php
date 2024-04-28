<?php

namespace App\Http\Controllers;

use App\Models\operation;
use Illuminate\Http\Request;
use App\Models\cliente;
use Illuminate\Support\Facades\Storage;

use function Laravel\Prompts\search;

class OperationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = cliente::all();
        $operations = Operation::all();
        return view('operations.index', compact('operations', 'clientes'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = cliente::all();
        // dd($clientes);
        return view('operations.create', compact('clientes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
        $document = $request->file('telecharger_fisher')->getClientOriginalName();
        $path = $request->file('telecharger_fisher')->storeAs('operation', $document, 'doc_operation');

        Operation::create([
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
        return redirect()->Route('operations.index');
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
