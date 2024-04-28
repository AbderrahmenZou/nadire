@extends('layout.app')

@section('titel')
    show page
@endsection

@section('titre')
    Document
@endsection

@section('soustitre')
    <a href="{{ route('DocumentComapnies.index') }}" class="nav-link active" aria-current="page">@yield('titre')</a>
@endsection

@section('titre')
    Document
@endsection

@section('continer')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">
                        Données du formulaire
                    </div>
                    <div class="card-body">
                        <p><strong>Document Company:</strong>
                            {{-- <img src="{{ asset('documents/' . $documentComapny->document_company) }}"
                                alt="{{ $documentComapny->document_company }}"> --}}
                            <iframe src="{{ asset('documents/' . $documentComapny->document_company) }}"
                                frameborder="0"></iframe>

                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
