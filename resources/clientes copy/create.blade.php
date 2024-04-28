@extends('layout.app')

@section('titel')
creat
@endsection

@section('titre')
clients
@endsection

@section('soustitre')
  <a href="{{route('clientes.index')}}" class="nav-link active" aria-current="page" >@yield('titre')</a> 
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

    <h1>create forn cliente</h1>
    <form action="{{route('clientes.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        {{-- @method('PUT') --}}
        

        <div class="form-group mb-3">
            <label for="nom_et_prenom">Nom et Prenom:</label>
            <input type="text" class="form-control mb-2" id="nom_et_prenom" name="nom_et_prenom" required>
        </div>

        <div class="form-group mb-3">
            <label for="N_Registre">N Registre:</label>
            <input type="text" class="form-control mt-3" id="N_Registre" name="N_Registre" required>
        </div>

        <div class="form-group mb-3">
            <label for="client_company">Client Company:</label>
            <input type="text" class="form-control mt-3" id="client_company" name="client_company" required>
        </div>

        <div class="form-group mb-3">
            <label for="nif">NIF:</label>
            <input type="text" class="form-control mt-3" id="nif" name="nif" required>
        </div>

        <div class="form-group mb-3">
            <label for="nis">NIS:</label>
            <input type="text" class="form-control mt-3" id="nis" name="nis" required>
        </div>

        <div>
            <label for="telecharger_fisher"> telecharger_fisher</label>
            <input type="file" name="telecharger_fisher"  required>
        </div>
        
        
        <div class="form-group mb-3">
            <button type="submit" class="btn btn-primary mt-3">create</button>
        </div>
    </form>
</div>

@endsection