<?php

namespace App\Http\Controllers;

use App\Models\DocumentComapny;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class DocumentComapnyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documents = DocumentComapny::all();
        return view('Document_Comapny.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $documents = DocumentComapny::all();
        return view('Document_Comapny.create', compact('documents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'document_company' => 'required',
        ]);
        $document = $request->file('document_company')->getClientOriginalName();
        $path = $request->file('document_company')->storeAs('company', $document, 'doc_company');


        // $pdf = PDF::loadView('pdf.usersPdf', $data);
        // return $pdf->download('users-lists.pdf');

        DocumentComapny::create([
            'document_company' => $path,

        ]);
        // Storage::disk('doc_company')->put('text.txt', $request->document_company);
        return redirect()->route('DocumentComapnies.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $documentComapny = DocumentComapny::find($id);
        return view('Document_Comapny.show', compact('documentComapny'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        $documentComapny = DocumentComapny::find($id);
        return view('Document_Comapny.edit', compact('documentComapny'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $documentComapny = DocumentComapny::find($id);
        if ($request->hasFile('document_company')) {
            if (Storage::disk('doc_company')->exists($documentComapny->document_company)) {
                Storage::disk('doc_company')->delete($documentComapny->document_company);
            }
            $document = $request->file('document_company')->getClientOriginalName();
            $path = $request->file('document_company')->storeAs('company', $document, 'doc_company');
            $documentComapny->document_company = $path;
        }
        $documentComapny->save();
        return redirect()->route('DocumentComapnies.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DocumentComapny::destroy($id);

        return redirect()->Route('DocumentComapnies.index');
    }

    public function searchDocumentComapny(Request $request)
    {
        $search = $request->search;
        $documents = DocumentComapny::where(function ($query) use ($search) {
            $query->where('id',  'like', "%$search%")
                ->orwhere('document_company',  'like', "%$search%");
        })
            ->get();
        return view('Document_Comapny.index', compact('documents', 'search'));
    }

    public function download(Request $request, $id)
    {
        $file = DocumentComapny::find($id);
        // $file->document_company;
        return response()->download(public_path('documents/' . $file->document_company));
    }
}
