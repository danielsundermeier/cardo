@extends('layouts.app')

@section('content')

    <div class="d-flex mb-1">
        <h2 class="col mb-0"><a class="text-body" href="/course">Kurse</a><span class="d-none d-md-inline"> > <a href="{{ $parent->path }}" class="text-body">{{ $parent->name }}</a> > {{ $model->at_formatted }}</span></h2>
        <div class="d-flex align-items-center">
            <a href="{{ $model->edit_path }}" class="btn btn-sm btn-primary" title="Bearbeiten"><i class="fas fa-edit"></i></a>
            <a href="{{ $parent->path }}" class="btn btn-sm btn-secondary ml-1">Übersicht</a>
            @if ($model->isDeletable())
                <form action="{{ $model->path }}" class="ml-1" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-sm btn-danger" title="Löschen"><i class="fas fa-trash"></i></button>
                </form>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card mb-3">
                <div class="card-header">{{ $parent->name }}</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-label"><b>Datum</b></div>
                        <div class="col-value">{{ $model->at_formatted }}</div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>Leiter</b></div>
                        <div class="col-value"><a href="{{ $model->instructor->path }}"layouts.guest>{{ $model->instructor->name }}</a></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col">
            <div class="card mb-3">
                <div class="card-header">Teilnehmer</div>
                <div class="card-body">
                    <courses-date-participation-table :model="{{ json_encode($model) }}" :parent="{{ json_encode($parent) }}" :partners="{{ json_encode($partners) }}" :last-date="{{ json_encode($last_date) }}"></courses-date-participation-table>
                </div>
            </div>
        </div>

    </div>

@endsection