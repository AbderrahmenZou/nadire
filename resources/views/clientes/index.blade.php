@extends('layout.app')

@section('titel')
    client
@endsection


@section('titre')
    clients
@endsection

@section('soustitre')
    <a href="{{ route('clientes.index') }}" class="nav-link active" aria-current="page">@yield('titre')</a>
@endsection


@section('continer')
    <div class="mt-4 container-md mt-5">
        <div class="text-center">
            <a href="{{ route('clientes.create') }}" class="btn btn-success">Create Client</a>
        </div>
        <form class="form-inline navbar navbar-light bg-light" method="get" action="{{ route('cliente.search') }}"
            value="{{ isset($search) ? $search : ' ' }}">
            <input name="search" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>

        <table class="table mt-4 text-center">
            <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">nom_et_prenom</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($clientes as $cliente)
                    <tr>
                        <td>{{ $cliente->id }}</td>
                        <td>{{ $cliente->nom_et_prenom }}</td>


                        <td>
                            <a href="{{ route('clientes.show', $cliente->id) }}" class="btn btn-info">view</a>
                            <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-primary">edite</a>

                            <form style="display: inline;" action="{{ route('clientes.destroy', $cliente->id) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>


                        </td>
                    </tr>
                @endforeach


    </div>
@endsection
