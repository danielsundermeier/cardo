@extends('layouts.app')

@section('content')

    @include('receipt.confirm-destroy', ['route' => $model->path])

    <div class="d-flex mb-3">
        <h2 class="col mb-0 pl-0"><a class="text-body" href="/bookkeeping/invoice">Rechnungen</a><span class="d-none d-md-inline"> > {{ $model->name }}</span></h2>
        <div class="d-flex align-items-center">
            <a href="/bookkeeping/receipt/{{ $model->id }}/pdf?hat_logo=0" class="btn btn-sm btn-secondary mr-1">Ohne Logo</a>
            @if ($model->is_paid)
                <form action="{{ $model->pay_path }}" class="mr-1" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-sm btn-secondary" title="Als nicht Bezahlt markieren">Unbezahlt</button>
                </form>
            @else
                <form action="{{ $model->pay_path }}" class="mr-1" method="POST">
                    @csrf
                    @method('PUT')

                    <button type="submit" class="btn btn-sm btn-success" title="Als Bezahlt markieren">Bezahlt</button>
                </form>
            @endif
            <a href="{{ url($model->edit_path) }}" class="btn btn-sm btn-primary mr-1"><i class="fas fa-edit"></i></a>
            <a href="{{ url('/bookkeeping/invoice') }}" class="btn btn-sm btn-secondary mr-1">Übersicht</a>
            <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#confirm-delete">Löschen</button>
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4"><b>Kontakt</b></div>
                <div class="col-md-8">{{ $model->partner->name }}</div>
            </div>
            <div class="row">
                <div class="col-md-4"><b>Fällig</b></div>
                <div class="col-md-8">{{ $model->dateDueForHumans }}</div>
            </div>
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

    <div class="card mb-5">
        <div class="card-body container">
            <object data="/bookkeeping/receipt/{{ $model->id }}/pdf" style="width: 100%; height: 600px">
                <center>PDF kann nicht angezeigt werden.</center>
            </object>
        </div>
    </div>

@endsection