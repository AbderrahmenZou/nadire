@extends('layout.app');

@section('titel')
    document
@endsection

@section('titre')
    Decument
@endsection

@section('soustitre')
    <a href="{{ route('DocumentComapnies.index') }}" class="nav-link active" aria-current="page">@yield('titre')</a>
@endsection
@section('continer')
    <div class="mt-4 container-md mt-5">
        <div class="text-center">
            <a href="{{ route('DocumentComapnies.create') }}" class="btn btn-success">Ajoute document</a>
        </div>

        <form class="form-inline navbar navbar-light bg-light" method="get" action="{{ route('DocumentComapnies.search') }}"
            value="{{ isset($search) ? $search : ' ' }}">
            <input name="search" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>

        <table class="table mt-4 text-center">
            <thead>
                <tr>
                    <th scope="col">id_decument</th>
                    <th scope="col">name document</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($documents as $document)
                    <tr>
                        <td>{{ $document->id }}</td>
                        <td>{{ $document->document_company }} </td>


                        <td>
                            <a href="{{ route('DocumentComapnies.show', $document->id) }}" class="btn btn-info">view</a>
                            <a href="{{ route('DocumentComapnies.edit', $document->id) }}" class="btn btn-primary">edite</a>
                            <form style="display: inline;" action="{{ route('DocumentComapnies.destroy', $document->id) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                            <a href="{{ route('DocumentComapnies.download', $document->id) }}"
                                class="btn btn-light">download</a>
                        </td>
                    </tr>
                @endforeach


    </div>
@endsection
