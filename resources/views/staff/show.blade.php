@extends('layouts.app')

@section('content')

@extends('layouts.app')

@section('content')

    <div class="d-flex mb-1">
        <h2 class="col mb-0"><a class="text-body" href="/staff">Mitarbeiter</a><span class="d-none d-md-inline"> > {{ $model->name }}</span></h2>
        <div class="d-flex align-items-center">
            <a href="{{ $model->edit_path }}" class="btn btn-primary" title="Bearbeiten"><i class="fas fa-edit"></i></a>
            <a href="/staff" class="btn btn-secondary ml-1">Übersicht</a>
            @if ($model->isDeletable())
                <form action="{{ $model->path }}" class="ml-1" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger" title="Löschen"><i class="fas fa-trash"></i></button>
                </form>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card mb-5">
                <div class="card-header">{{ $model->name }}</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-label"><b>Name</b></div>
                                <div class="col-value">{{ $model->name }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@endsection