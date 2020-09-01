@extends('layouts.app')

@section('content')

    <div class="d-flex mb-1">
        <h2 class="col mb-0"><a class="text-body" href="/{{ $base_view_path }}">Lieferanten</a><span class="d-none d-md-inline"> > {{ $model->name }}</span></h2>
        <div class="d-flex align-items-center">
            <a href="{{ route($base_view_path . '.show', [$base_view_path => $model->id]) }}" class="btn btn-secondary ml-1">Übersicht</a>
        </div>
    </div>
    <form action="{{ route($base_view_path . '.show', [$base_view_path => $model->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col">
                <div class="card mb-5">
                    <div class="card-header">{{ $model->name }}</div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="firstname">Vorname</label>
                            <input type="text" class="form-control @error('firstname') is-invalid @enderror" id="firstname" name="firstname" placeholder="firstname" value="{{ $model->firstname }}">
                            @error('firstname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="lastname">Nachname</label>
                            <input type="text" class="form-control @error('lastname') is-invalid @enderror" id="lastname" name="lastname" placeholder="lastname" value="{{ $model->lastname }}">
                            @error('lastname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Speichern</button>
                    </div>
                </div>
            </div>
        </div>

    </form>

@endsection