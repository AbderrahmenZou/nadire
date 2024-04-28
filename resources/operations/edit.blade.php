@extends('layout.app')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.2/css/bootstrap.min.css"
    integrity="sha512-CpIKUSyh9QX2+zSdfGP+eWLx23C8Dj9/XmHjZY2uDtfkdLGo0uY12jgcnkX9vXOgYajEKb/jiw67EYm+kBf+6g=="
    crossorigin="anonymous" referrerpolicy="no-referrer">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" crossorigin="anonymous"
    referrerpolicy="no-referrer"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha384-$HASH$"
    crossorigin="anonymous"></script>


{{-- select2 cdn --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
    integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
    integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />


@section('titel')
    creat
@endsection

@section('titre')
    operation
@endsection

@section('soustitre')
    <a href="{{ route('operations.index') }}" class="nav-link active" aria-current="page">@yield('titre')</a>
@endsection

@section('continer')
    <div class="container mt-5">
        <h1>operation edite</h1>
        <form action="{{ route('operations.update', $operation->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- @method('PUT') --}}
            <div class="form-group">
                <label for="id_cliente">ID_cliente:</label>
                {{-- <input type="text" class="form-control" id="id_company" name="id_company" required> --}}
                <select class="tags form-control" name="id_cliente" id="id_cliente">
                    <option value="{{ $operation->id_cliente }}">
                        {{ $operation->id_cliente . '-' . $operation->cliente->nom_et_prenom }}</option>

                </select>

                @error('id_cliente')
                    <span class="text-danger">{{ $message }}</span>
                @enderror

            </div>

            <div class="form-group">
                <label for="client_company">Client Company:</label>
                <input type="text" class="form-control" value="{{ $operation->client_company }}" id="client_company"
                    name="client_company" required>
            </div>

            <div class="form-group">
                <label for="N_declaration">N Declaration:</label>
                <input type="text" class="form-control" value="{{ $operation->N_declaration }}" id="N_declaration"
                    name="N_declaration" required>
            </div>

            <div class="form-group">
                <label for="La_Date">La Date:</label>
                <input type="date" class="form-control" value="{{ $operation->La_Date }}" id="La_Date" name="La_Date"
                    required>
            </div>

            <div class="form-group">
                <label for="N_Facture">N Facture:</label>
                <input type="text" class="form-control" value="{{ $operation->N_Facture }}" id="N_Facture"
                    name="N_Facture" required>
            </div>

            <div class="form-group">
                <label for="Montant">Montant:</label>
                <input type="text" class="form-control" value="{{ $operation->N_Bill }}" id="Montant" name="Montant"
                    required>
            </div>

            <div class="form-group">
                <label for="N_Bill">N Bill:</label>
                <input type="text" class="form-control" value="{{ $operation->N_Bill }}" id="N_Bill" name="N_Bill"
                    required>
            </div>

            <div class="form-group">
                <label for="N_Repartoire">N Repartoire:</label>
                <input type="text" class="form-control" value="{{ $operation->N_Repartoire }}" id="N_Repartoire"
                    name="N_Repartoire" required>
            </div>

            <div class="form-group">
                <label for="telecharger_fisher">Télécharger Fisher:</label>
                <input type="file" class="form-control-file"
                    value="{{ asset('documents/' . $operation->telecharger_fisher) }}" id="telecharger_fisher"
                    name="telecharger_fisher">
                {{-- <img src="{{ asset('documents/' . $operation->telecharger_fisher) }}"
                    alt="{{ $operation->telecharger_fisher }}"> --}}
                <iframe src="{{ asset('documents/' . $operation->telecharger_fisher) }}" frameborder="0"></iframe>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
@endsection
<script>
    $(document).ready(function() {
        $('.id_cliente').select2({
            placeholder: "Select",
            allowClear: true,
        });
        $('#id_cliente').select2({
            ajax: {
                url: "{{ route('get-client') }}",
                type: "post",
                delay: 250,
                dataType: 'json',
                data: function(params) {
                    return {
                        id: params.term,
                        name: params.term, // corrected here
                        _token: "{{ csrf_token() }}",
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.id + '-' + item.nom_et_prenom,
                            }
                        })
                    }
                }
            }
        });
    });
</script>
