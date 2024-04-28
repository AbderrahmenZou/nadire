@extends('layout.app')

@section('titel')
    show page
@endsection

@section('titre')
    operation
@endsection

@section('soustitre')
    <a href="{{ route('operations.index') }}" class="nav-link active" aria-current="page">@yield('titre')</a>
@endsection

@section('titre')
    operation
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
                        <p><strong>ID Company:</strong> {{ $operation->id }} </p>
                        <p><strong>Client Company:</strong> {{ $operation->client_company }}</p>
                        <p><strong>N Declaration:</strong> {{ $operation->N_declaration }}</p>
                        <p><strong>La Date:</strong> {{ $operation->La_Date }}</p>
                        <p><strong>N Facture:</strong> {{ $operation->N_Facture }}</p>
                        <p><strong>Montant:</strong> {{ $operation->Montant }}</p>
                        <p><strong>N Bill:</strong> {{ $operation->N_Bill }}</p>
                        <p><strong>N Repartoire:</strong> {{ $operation->N_Repartoire }}</p>
                        <p><strong>Télécharger Fisher:</strong>
                            {{-- <img
                                src="{{ asset('documents/' . $operation->telecharger_fisher) }}"
                                alt="{{ $operation->telecharger_fisher }}"> --}}
                            <iframe src="{{ asset('documents/' . $operation->telecharger_fisher) }}"
                                frameborder="0"></iframe>
                            <a href="{{ route('operations.download', $operation->id) }}" class="btn btn-light">download</a>

                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
