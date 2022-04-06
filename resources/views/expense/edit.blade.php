@extends('layouts.app')

@section('title', 'Ausgaben > ' . $model->name)

@section('content')

    @include('receipt.confirm-destroy', ['route' => $model->path])

    <div class="container-fluid">
        <div class="row">
            <div class="col"></div>
            <a href="/bookkeeping/receipt/{{ $model->id }}/pdf" class="btn btn-sm btn-secondary mr-1" title="Vorschau"><i class="fas fa-file-pdf"></i></a>
            <a href="/bookkeeping/receipt/{{ $model->id }}/download" class="btn btn-sm btn-secondary mr-1" title="Download"><i class="fas fa-download"></i></a>
            <div class="dropdown mr-1">
                <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-ellipsis-h"></i> Mehr
                </button>
                <div class="dropdown-menu">
                    <h6 class="dropdown-header">Bearbeiten</h6>
                    <button class="dropdown-item pointer" data-toggle="modal" data-target="#confirm-delete">Löschen</button>
                </div>
            </div>
            <a href="{{ url('/bookkeeping/expense') }}" class="btn btn-sm btn-secondary">Übersicht</a>
        </div>
    </div>

    <form action="{{ $model->path }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col">
                <div class="card my-3">
                    <div class="card-header">Allgemein</div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Nummer</label>
                            <input type="text" class="form-control {{ ($errors->has('name') ? 'is-invalid' : '') }}" id="name" name="name" value="{{ $model->name }}">
                            @if ($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="date_formatted">Datum</label>
                            <input type="text" class="form-control {{ ($errors->has('date_formatted') ? 'is-invalid' : '') }}" id="date_formatted" name="date_formatted" value="{{ $model->date_formatted }}">
                            @if ($errors->has('date_formatted'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('date_formatted') }}
                                </div>
                            @endif
                        </div>

                        <receipt-edit-address selected-address="{{ $model->address }}" :selected-partner-id="{{ $model->partner_id }}" :partners="{{ json_encode($partners) }}"></receipt-edit-address>

                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-sm btn-primary">Speichern</button>

    </form>
    <br />
    <div class="row mb-3">
        <div class="col-md-4 d-flex align-items-center justify-content-center">
                <userfileable-single :model="{{ json_encode($model) }}"></userfileable-single>
            </div>
            <div class="col">
                <receipt-line-table :show-tax="{{ config('app.show_tax') ? '1' : '0' }}" :model="{{ json_encode($model) }}" :options="{{ json_encode($items) }}" :units="{{ json_encode($units) }}" :selected-partner-id="{{ $model->partner_id }}" :partners="{{ json_encode($partners) }}"></receipt-line-table>
            </div>
        </div>
    </div>


@endsection