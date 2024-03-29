@extends('layouts.app')

@section('content')

    <div class="d-flex mb-1">
        <h2 class="col mb-0"><a class="text-body" href="/{{ $base_view_path }}">Kunden</a><span class="d-none d-lg-inline"> > {{ $model->name }} ({{ $model->is_active ? 'aktiv' : 'inaktiv' }})</span></h2>
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
        <div class="col-12 col-lg-6">

            @include('partner.show.address', ['model' => $model])

        </div>

        <div class="col-12 col-lg-6">
            <div class="card mb-3">
                <div class="card-header">Kurse</div>
                <div class="card-body">
                    <partner-course-table :model="{{ json_encode($model) }}" :courses="{{ json_encode($courses) }}"></partner-course-table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card mb-3">
                <div class="card-header">Gesundheitsdaten</div>
                <div class="card-body">
                    <partner-healthdata-table :partner="{{ json_encode($model) }}"></partner-healthdata-table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card mb-3">
                <div class="card-header">Anamnesebogen</div>
                <div class="card-body">
                    <partner-history-table :model="{{ json_encode($model) }}"></partner-history-table>
                </div>
            </div>
        </div>
    </div>

    <userfileable-table uri="/client" :model="{{ json_encode($model) }}" token="{{ csrf_token() }}"></userfileable-table>

    <div class="row">
        <div class="col">
            <div class="card mb-3">
                <div class="card-header">Rechnungen</div>
                <div class="card-body">
                    <partner-receipt-table uri="/bookkeeping/invoice" :model="{{ json_encode($model) }}"></partner-receipt-table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card mb-3">
                <div class="card-header">Korrekturen</div>
                <div class="card-body">
                    <partner-participations-corrections-table :model="{{ json_encode($model) }}" :courses="{{ json_encode($courses) }}"></partner-participations-corrections-table>
                </div>
            </div>
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