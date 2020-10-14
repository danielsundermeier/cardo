@extends('layouts.app')

@section('title', 'Partner > Import')

@section('content')

    <form action="{{ route('partner.import.csv.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="import_file">Datei</label>
            <input type="file" class="form-control-file {{ ($errors->has('import_file') ? 'is-invalid' : '') }}" id="import_file" name="import_file">
            @if ($errors->has('import_file'))
                <div class="invalid-feedback">
                    {{ $errors->first('import_file') }}
                </div>
            @endif
        </div>

        <button class="btn btn-primary" type="submit">Importieren</button>
    </form>

    @if (session('result'))
        @include('import.result.' . $type, ['result' => session('result')])
    @endif

@endsection