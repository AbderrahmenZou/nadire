@extends('layout.app')

@section('titel')
    show
@endsection


@section('titre')
    clientes
@endsection

@section('soustitre')
    <a href="{{ route('clientes.index') }}" class="nav-link active" aria-current="page">@yield('titre')</a>
@endsection

@section('continer')

    <body>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card">
                        <div class="card-header">
                            Form Data
                        </div>
                        <div class="card-body">
                            <p><strong>ID Client:</strong> {{ $cliente->id }}</p>
                            <p><strong>Name and Surname:</strong> {{ $cliente->nom_et_prenom }}</p>
                            <p><strong>Registration Number:</strong> {{ $cliente->N_Registre }}</p>
                            <p><strong>Client Company:</strong> {{ $cliente->client_company }}</p>
                            <p><strong>NIF:</strong> {{ $cliente->nif }}</p>
                            <p><strong>NIS:</strong> {{ $cliente->nis }}</p>
                            <p><strong>Download Fisher File:</strong>
                                {{-- <img
                                    src="{{ asset('documents/' . $cliente->telecharger_fisher) }}"
                                    alt="{{ $cliente->telecharger_fisher }}"> --}}
                                <iframe src="{{ asset('documents/' . $cliente->telecharger_fisher) }}"
                                    frameborder="0"></iframe>
                                <a href="{{ route('clientes.download', $cliente->id) }}" class="btn btn-light">download</a>
                            </p>
                        </div>


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
                                            <a href="{{ route('operations.show', $operation->id) }}"
                                                class="btn btn-info">view</a>
                                            <a href="{{ route('operations.edit', $operation->id) }}"
                                                class="btn btn-primary">edite</a>
                                            <form style="display: inline;"
                                                action="{{ route('operations.destroy', $operation->id) }}" method="POST">
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
                </div>
            </div>
        </div>
    </body>
@endsection
