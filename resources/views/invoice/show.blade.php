@extends('layouts.app')

@section('title', 'Rechnungen > ' . $model->name)

@section('content')
    <div class="row text-right mb-3">
        <div class="col"></div>
        <div class="col-sm col-sm-auto">
            <a href="{{ url($model->edit_path) }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
            <a href="{{ url('/bookkeeping/invoice') }}" class="btn btn-secondary">Übersicht</a>
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-body">
            <div class="row">

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4"><b>Kontakt</b></div>
                        <div class="col-md-8">{{ $model->partner->name }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><b>Fällig</b></div>
                        <div class="col-md-8">{{ $model->dateDueForHumans }}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4"><b>Offen</b></div>
                        <div class="col-md-8">{{ number_format($model->outstandingBalance / 100, 2, ',', '.') }} €</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><b>Betrag netto</b></div>
                        <div class="col-md-8">{{ number_format($model->gross / 100, 2, ',', '.') }} €</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><b>USt.</b></div>
                        <div class="col-md-8">{{ number_format($model->tax_value / 100, 2, ',', '.') }} €</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><b>Betrag brutto</b></div>
                        <div class="col-md-8">{{ number_format($model->gross / 100, 2, ',', '.') }} €</div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-body container">
            <object data="/bookkeeping/receipt/{{ $model->id }}/pdf" style="width: 100%; height: 600px">
                <center>PDF kann nicht angezeigt werden.</center>
            </object>
        </div>
    </div>

@endsection