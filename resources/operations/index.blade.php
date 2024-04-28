@extends('layout.app');

@section('titel')
    index
@endsection

@section('titre')
    operation
@endsection

@section('soustitre')
    <a href="{{ route('operations.index') }}" class="nav-link active" aria-current="page">@yield('titre')</a>
@endsection

@section('continer')
    <div class="mt-4 container-md mt-5">
        <div class="text-center">
            <a href="{{ route('operations.create') }}" class="btn btn-success">Create operations</a>
        </div>

        <form class="form-inline navbar navbar-light bg-light" method="get" action="{{ route('operations.search') }}"
            value="{{ isset($search) ? $search : ' ' }}">
            <input name="search" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>

        <table class="table mt-4 text-center">
            <thead>
                <tr>
                    <th scope="col">id_operation</th>
                    <th scope="col">id_et_nom_de_cliente</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($operations as $operation)
                    <tr>
                        <td>{{ $operation->id }}</td>
                        <td>{{ $operation->id_cliente }} - {{ $operation->cliente->nom_et_prenom }}</td>


                        <td>
                            <a href="{{ route('operations.show', $operation->id) }}" class="btn btn-info">view</a>
                            <a href="{{ route('operations.edit', $operation->id) }}" class="btn btn-primary">edite</a>
                            <form style="display: inline;" action="{{ route('operations.destroy', $operation->id) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>



                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
