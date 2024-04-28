@extends('layout.app')


@section('titel')
    creat
@endsection

@section('titre')
    document
@endsection

@section('soustitre')
    <a href="{{ route('DocumentComapnies.index') }}" class="nav-link active" aria-current="page">@yield('titre')</a>
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
        <h1>document create</h1>
        <form action="{{ route('DocumentComapnies.update', $documentComapny->id) }}" method="post"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="document_company">Télécharger Fisher:</label>
                <input type="file" class="form-control-file" id="document_company" name="document_company"
                    value="{{ asset('documents/' . $documentComapny->document_company) }}">
                {{-- <img src="{{ asset('documents/' . $documentComapny->document_company) }}"
                    alt="{{ $documentComapny->document_company }}"> --}}
                <iframe src="{{ asset('documents/' . $documentComapny->document_company) }}" frameborder="0"></iframe>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>

@endsection
