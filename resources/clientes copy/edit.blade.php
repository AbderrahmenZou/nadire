@extends('layout.app')

@section('titel')
    creat
@endsection

@section('titre')
    operation
@endsection

@section('soustitre')
    <a href="{{ route('clientes.index') }}" class="nav-link active" aria-current="page">@yield('titre')</a>
@endsection


@section('continer')




    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container mt-5">
        <h1>edite form cliente</h1>
        <form action="{{ route('clientes.update', $cliente->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')


            <div class="form-group mb-3">
                <label for="nom_et_prenom">Nom et Prenom:</label>
                <input type="text" class="form-control mb-2" id="nom_et_prenom" value="{{ $cliente->nom_et_prenom }}"
                    name="nom_et_prenom" required>
            </div>

            <div class="form-group mb-3">
                <label for="N_Registre">N Registre:</label>
                <input type="text" class="form-control mt-3" id="N_Registre" value="{{ $cliente->N_Registre }}"
                    name="N_Registre" required>
            </div>

            <div class="form-group mb-3">
                <label for="client_company">Client Company:</label>
                <input type="text" class="form-control mt-3" id="client_company" value="{{ $cliente->client_company }}"
                    name="client_company" required>
            </div>

            <div class="form-group mb-3">
                <label for="nif">NIF:</label>
                <input type="text" class="form-control mt-3" id="nif" value="{{ $cliente->nif }}" name="nif"
                    required>
            </div>

            <div class="form-group mb-3">
                <label for="nis">NIS:</label>
                <input type="text" class="form-control mt-3" id="nis" value="{{ $cliente->nis }}" name="nis"
                    required>
            </div>

            <div class="form-group">
                <label for="telecharger_fisher">Télécharger Fisher:</label>
                <input type="file" class="form-control-file"{{-- value="{{ $cliente->telecharger_fisher }}" --}} id="telecharger_fisher"
                    name="telecharger_fisher" value="{{ asset('documents/' . $cliente->telecharger_fisher) }}">
                {{-- <img src="{{ asset('documents/' . $cliente->telecharger_fisher) }}"
                    alt="{{ $cliente->telecharger_fisher }}"> --}}
                <iframe src="{{ asset('documents/' . $cliente->telecharger_fisher) }}" frameborder="0"></iframe>
            </div>

            <div class="form-group mb-3">
                <button type="submit" class="btn btn-primary mt-3">Update</button>
            </div>
        </form>
    </div>

@endsection
