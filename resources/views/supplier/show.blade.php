@extends('layouts.app')

@section('content')

    <div class="d-flex mb-1">
        <h2 class="col mb-0"><a class="text-body" href="/{{ $base_view_path }}">Lieferanten</a><span class="d-none d-md-inline"> > {{ $model->name }}</span></h2>
        <div class="d-flex align-items-center">
            <a href="{{ route($base_view_path . '.edit', [$base_view_path => $model->id]) }}" class="btn btn-sm btn-primary" title="Bearbeiten"><i class="fas fa-edit"></i></a>
            <a href="/{{ $base_view_path }}" class="btn btn-sm btn-secondary ml-1">Übersicht</a>
            @if ($model->isDeletable())
                <form action="{{ route($base_view_path . '.destroy', [$base_view_path => $model->id]) }}" class="ml-1" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-sm btn-danger" title="Löschen"><i class="fas fa-trash"></i></button>
                </form>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-6">

            @include('partner.show.address', ['model' => $model])

        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card mb-3">
                <div class="card-header">Kommentare</div>
                <div class="card-body">
                    <comments :model="{{ json_encode($model) }}"></comments>
                </div>
            </div>
        </div>
    </div>

@endsection