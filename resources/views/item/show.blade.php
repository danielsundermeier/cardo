@extends('layouts.app')

@section('content')

@extends('layouts.app')

@section('content')

    <div class="d-flex mb-1">
        <h2 class="col mb-0"><a class="text-body" href="/item">Produkte</a><span class="d-none d-md-inline"> > {{ $model->name }}</span></h2>
        <div class="d-flex align-items-center">
            <a href="{{ $model->edit_path }}" class="btn btn-primary" title="Bearbeiten"><i class="fas fa-edit"></i></a>
            <a href="/item" class="btn btn-secondary ml-1">Übersicht</a>
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
                        <div class="col-label"><b>Name</b></div>
                        <div class="col-value">{{ $model->name }}</div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>Einheit</b></div>
                        <div class="col-value">{{ $model->unit->name }} ({{ $model->unit->abbreviation }})</div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>Preis/Einheit</b></div>
                        <div class="col-value">{{ $model->unit_price_formatted }} €</div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>Buchungskonto Rechnung</b></div>
                        <div class="col-value">{{ $model->revenue_account_number }}</div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>Buchungskonto Ausgabe</b></div>
                        <div class="col-value">{{ $model->expense_account_number }}</div>
                    </div>
                    @if ($model->course_id)
                        <div class="row">
                            <div class="col-label"><b>Verknüpfter Kurs</b></div>
                            <div class="col-value"><a href="{{ $model->course->path }}"layouts.guest>{{ $model->course->name }}</a></div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

@endsection

@endsection