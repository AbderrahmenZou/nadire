<?php

namespace App\Http\Controllers\API;

use App\Models\DocumentComapny;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade as PDF;

class DocumentComapnyController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $documents = DocumentComapny::all();

        // التحقق مما إذا كانت هناك بيانات عمليات متاحة
        if ($documents->isEmpty()) {
            return response()->json(['message' => 'No documents found'], 404);
        }

        // Return JSON response with operations data
        return response()->json(['message' => 'documents retrieved successfully', 'documents' => $documents], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     $documents = DocumentComapny::all();
    //     return view('Document_Comapny.create', compact('documents'));
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'document_company' => 'required',
        ]);

        $document = $request->file('document_company')->getClientOriginalName();

        // Attempt to store the file
        try {
            $path = $request->file('document_company')->storeAs('company', $document, 'doc_company');
        } catch (\Exception $e) {
            // In case storing the file fails
            return response()->json(['error' => 'An error occurred while uploading the file'], 500);
        }

        // $pdf = PDF::loadView('pdf.usersPdf', $data);
        // return $pdf->download('users-lists.pdf');
        $DocumentComapny = DocumentComapny::create([
            'document_company' => $path,

        ]);

        if ($DocumentComapny) {
            // Return a success response with the created DocumentComapny data
            return response()->json(['message' => 'DocumentComapny created successfully', 'DocumentComapny' => $DocumentComapny], 201);
        } else {
            // Return an error response if creating the operation fails
            return response()->json(['error' => 'Failed to create the Document Comapny'], 500);
        }


        // Storage::disk('doc_company')->put('text.txt', $request->document_company);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {

        $documentComapny = DocumentComapny::find($request->id);

        if (!$documentComapny) {
            // Return a not found response if Cliente does not exist
            return response()->json(['error' => 'document Comapny not found'], 404);
        } else {
            return response()->json(['message' => 'document Comapny exists', 'documentComapny' => $documentComapny], 200);
        }



        // return view('Document_Comapny.show', compact('documentComapny'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit($id)
    // {

    //     $documentComapny = DocumentComapny::find($id);


    //     if (!$documentComapny) {
    //         // Return a not found response if Cliente does not exist
    //         return response()->json(['error' => 'document Comapny not found'], 404);
    //     } else {
    //         return response()->json(['message' => 'document Comapny exists', 'documentComapny' => $documentComapny], 200);
    //     }

    //     // return view('Document_Comapny.edit', compact('documentComapny'));
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {



        $request->validate([
            'document_company' => 'required',
        ]);
        $documentComapny = DocumentComapny::find($request->id);
        if (!$documentComapny) {
            // Return a not found response if Cliente does not exist
            return response()->json(['error' => 'document Comapny not found'], 404);
        }

        if ($request->hasFile('document_company')) {
            if (Storage::disk('doc_company')->exists($documentComapny->document_company)) {
                Storage::disk('doc_company')->delete($documentComapny->document_company);
            }
            $document = $request->file('document_company')->getClientOriginalName();
            $path = $request->file('document_company')->storeAs('company', $document, 'doc_company');
            $documentComapny->document_company = $path;
        }
        $documentComapny->save();

        return response()->json(['message' => 'Document Comapny updated successfully', 'documentComapny' => $documentComapny], 200);

        // return redirect()->route('DocumentComapnies.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {

        $request->validate([
            'id' => 'required',
        ]);

        try {
            // Find the operation by ID
            $documentComapny = DocumentComapny::find($request->id);

            // Delete the operation
            $documentComapny->delete();

            // Return a success response
            return response()->json(['message' => 'Document Comapny deleted successfully', 'documentComapny' => $documentComapny], 200);
        } catch (\Exception $e) {
            // Return an error response if the operation does not exist or if there is any other error
            return response()->json(['error' => 'Failed to delete Document Comapny'], 500);
        }
    }

    public function searchDocumentComapny(Request $request)
    {


        $request->validate([
            'search' => 'required',
        ]);
        $search = $request->search;
        $documents = DocumentComapny::where(function ($query) use ($search) {
            $query->where('id',  'like', "%$search%")
                ->orwhere('document_company',  'like', "%$search%");
        })->get();

        if ($documents->isEmpty()) {
            // إرجاع رسالة تعليمية في حالة عدم وجود نتائج
            return response()->json(['message' => 'No results found for the specified search criteria']);
        } else {
            return response()->json(['message' => 'documents retrieved successfully', 'documents' => $documents], 200);
        }
    }

    public function download(Request $request)
    {

        $id = $request->id;
        $file = DocumentComapny::find($id);
        // $file->document_company;
        if (!$file) {
            return response()->json(['message' => "document not found", 'file' => $file], 404);
        }

        $filePath = $file->document_company;
        // Check if the file exists
        if (!Storage::disk('doc_company')->exists($filePath)) {
            return response()->json(['msg' => 'File not found'], 404);
        }

        return response()->download(public_path('documents/' . $filePath));
    }
}
