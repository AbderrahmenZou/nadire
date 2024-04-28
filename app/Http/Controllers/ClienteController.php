<?php

namespace App\Http\Controllers;

use App\Models\cliente;
use Illuminate\Http\Request;
use App\Models\Operation;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClienteRequest;
use App\Http\Requests\OperationRequest;
use App\Http\Requests\DocumentClienteRequest;
use App\Http\Requests\DocumentCompanyRequest;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::all();
        return view('clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // تحقق من صحة البيانات
        $validatedData = $request->validate([
            'nom_et_prenom' => 'required',
            'N_Registre' => 'required',
            'client_company' => 'required',
            'nif' => 'required',
            'nis' => 'required',
            'telecharger_fisher' => 'required|file', // تأكد من أنه ملف
        ]);
    
        // تحديد اسم الملف الأصلي
        $documentName = $request->file('telecharger_fisher')->getClientOriginalName();
    
        // محاولة تخزين الملف
        try {
            $path = $request->file('telecharger_fisher')->storeAs('client', $documentName, 'doc_client');
        } catch (\Exception $e) {
            // في حالة فشل عملية تخزين الملف
            return redirect()->back()->withInput()->withErrors(['telecharger_fisher' => 'حدث خطأ أثناء تحميل الملف']);
        }
    
        // إنشاء مورد Cliente جديد
        Cliente::create([
            'nom_et_prenom' => $validatedData['nom_et_prenom'],
            'N_Registre' => $validatedData['N_Registre'],
            'client_company' => $validatedData['client_company'],
            'nif' => $validatedData['nif'],
            'nis' => $validatedData['nis'],
            'telecharger_fisher' => $path, // تخزين مسار الملف المحمل
        ]);
    
        // توجيه المستخدم إلى القائمة بعد إنشاء العميل بنجاح
        return redirect()->route('clientes.index')->with('success', 'تم إنشاء العميل بنجاح');
    }
    


                            







        // Storage::disk('client')->put('text.txt', $request->document_company);

        // $formfileds['telecharger_fisher'] = $request->file('telecharger_fisher')->store('telecharger_fisher', 'public');
        // return reponse('donne telechargement done');
    //     return redirect()->Route('clientes.index');
    // }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cliente = Cliente::findorfail($id);
        $operations = Operation::where('id_cliente', $id)->get();

        return view('clientes.show', compact('cliente', 'operations'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        $cliente = Cliente::findorfail($id);

        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $cliente = Cliente::findorfail($id);
        $cliente->nom_et_prenom = $request->input('nom_et_prenom');
        $cliente->N_Registre = $request->input('N_Registre');
        $cliente->client_company = $request->input('client_company');
        $cliente->nif = $request->input('nif');
        $cliente->nis = $request->input('nis');
        if ($request->hasFile('telecharger_fisher')) {
            if (Storage::disk('doc_client')->exists($cliente->telecharger_fisher)) {
                Storage::disk('doc_client')->delete($cliente->telecharger_fisher);
            }
            $document = $request->file('telecharger_fisher')->getClientOriginalName();
            $path = $request->file('telecharger_fisher')->storeAs('client', $document, 'doc_client');
            $cliente->telecharger_fisher = $path;
        }
        $cliente->save();
        return redirect()->Route('clientes.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        cliente::destroy($id);
        return redirect()->Route('clientes.index');
    }


    public function searchCliente(Request $request)
    {
        $search = $request->search;
        $clientes = cliente::where(function ($query) use ($search) {
            $query->where('nom_et_prenom', 'like', "%$search%")
                ->orwhere('N_Registre',  'like', "%$search%")
                ->orwhere('nif',  'like', "%$search%")
                ->orwhere('nis',  'like', "%$search%")
                ->orwhere('telecharger_fisher',  'like', "%$search%");
        })
            // ->orWhereDoesntHave('operation', function ($query) use ($search) {
            //     $query->where('N_Facture',  'like', "%$search%")
            //         ->orwhere('N_Bill',  'like', "%$search%")
            //         ->orwhere('N_Repartoire',  'like', "%$search%");
            // })

            ->get();

        return view('clientes.index', compact('clientes', 'search'));
    }


    public function downloadClient(Request $request, $id)
    {
        $file = cliente::find($id);
        // $file->telecharger_fisher;
        return response()->download(public_path('documents/' . $file->telecharger_fisher));
    }
}
