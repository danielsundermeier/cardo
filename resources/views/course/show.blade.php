@extends('layouts.app')

@section('content')

    <div class="d-flex mb-1">
        <h2 class="col mb-0"><a class="text-body" href="/course">Kurse</a><span class="d-none d-md-inline"> > {{ $model->name }}</span></h2>
        <div class="d-flex align-items-center">
            <a href="{{ $model->participations_index_path }}" class="btn btn-sm btn-secondary" title="Teilnehmer">Teilnehmer</a>
            <a href="{{ $model->edit_path }}" class="btn btn-sm btn-primary ml-1" title="Bearbeiten"><i class="fas fa-edit"></i></a>
            <a href="/course" class="btn btn-sm btn-secondary ml-1">Übersicht</a>
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
        <div class="col-12 col-lg-6">
            <div class="card mb-3">
                <div class="card-header">{{ $model->name }}</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-label"><b>Name</b></div>
                        <div class="col-value">{{ $model->name }}</div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>Leiter</b></div>
                        <div class="col-value"><a href="{{ $model->instructor->path }}"layouts.guest>{{ $model->instructor->name }}</a></div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>Zeit</b></div>
                        <div class="col-value">{{ $model->day_formatted }} {{ $model->time_formatted }}</div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>Dauer</b></div>
                        <div class="col-value">{{ $model->duration_formatted }}</div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>Aktiv</b></div>
                        <div class="col-value">{{ $model->is_active_formatted }}</div>
                    </div>
                    <div>
                        {!! nl2br($model->description) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            @if($model->item)
                <div class="card mb-3">
                    <div class="card-header">Produkt</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="row">
                                    <div class="col-label"><b>Name</b></div>
                                    <div class="col-value"><a href="{{ $model->item->path }}">{{ $model->item->name }}</a></div>
                                </div>
                                <div class="row">
                                    <div class="col-label"><b>Preis</b></div>
                                    <div class="col-value">{{ $model->item->unit_price_formatted }} €</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-danger" role="alert">
                    Mit diesem Kurs ist kein <a href="/item">Produkt</a> verknüpft!
                </div>
            @endif

            @if($model->subscription_item)
                <div class="card mb-3">
                    <div class="card-header">Abo Produkt</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="row">
                                    <div class="col-label"><b>Name</b></div>
                                    <div class="col-value"><a href="{{ $model->subscription_item->path }}">{{ $model->subscription_item->name }}</a></div>
                                </div>
                                <div class="row">
                                    <div class="col-label"><b>Preis</b></div>
                                    <div class="col-value">{{ $model->subscription_item->unit_price_formatted }} €</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-danger" role="alert">
                    Mit diesem Kurs ist kein <a href="/item">Abo Produkt</a> verknüpft!
                </div>
            @endif
        </div>


    </div>

    <div class="row">
        <div class="col-12 col-xl-6">
            <div class="card mb-3">
                <div class="card-header">Teilnehmer</div>
                <div class="card-body">
                    <courses-participant-table :model="{{ json_encode($model) }}" :partners="{{ json_encode($partners) }}"></courses-participant-table>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="card mb-3">
                <div class="card-header">Veranstaltungen</div>
                <div class="card-body">
                    <courses-date-table :model="{{ json_encode($model) }}"></courses-date-table>
                </div>
            </div>
        </div>

    </div>

@endsection