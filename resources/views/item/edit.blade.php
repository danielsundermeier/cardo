@extends('layouts.app')

@section('content')

    <div class="d-flex mb-1">
        <h2 class="col mb-0"><a class="text-body" href="/item">Produkte</a><span class="d-none d-md-inline"> > {{ $model->name }}</span></h2>
        <div class="d-flex align-items-center">
            <a href="{{ $model->path }}" class="btn btn-sm btn-secondary ml-1">Übersicht</a>
        </div>
    </div>
    <form action="{{ $model->path }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col">
                <div class="card mb-5">
                    <div class="card-header">{{ $model->name }}</div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Name" value="{{ $model->name }}">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="unit_id">Einheit</label>
                            <select class="form-control {{ ($errors->has('unit_id') ? 'is-invalid' : '') }}" id="unit_id" name="unit_id">
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id}}" {{ $model->unit_id == $unit->id ? 'selected="selected"' : '' }}>{{ $unit->name }} ({{ $unit->abbreviation }})</option>
                                @endforeach
                            </select>
                            @error('unit_id')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="unit_price_formatted">Preis/Einheit</label>
                            <input type="text" class="form-control {{ ($errors->has('unit_price_formatted') ? 'is-invalid' : '') }}" id="unit_price_formatted" name="unit_price_formatted" value="{{ $model->unit_price_formatted }}">
                            @error('unit_price_formatted')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="tax">USt.</label>
                            <select class="form-control @error('tax') is-invalid @enderror" id="tax" name="tax">
                                <option value="0.19" {{ $model->tax == 0.19 ? 'selected="selected"' : '' }}>19%</option>
                                <option value="0.16" {{ $model->tax == 0.16 ? 'selected="selected"' : '' }}>16%</option>
                                <option value="0.07" {{ $model->tax == 0.07 ? 'selected="selected"' : '' }}>7%</option>
                                <option value="0" {{ $model->tax == 0 ? 'selected="selected"' : '' }}>0%</option>
                            </select>
                            @error('tax')
                                <div class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="revenue_account_number">Buchungskonto Rechnung</label>
                            <input type="text" class="form-control {{ ($errors->has('revenue_account_number') ? 'is-invalid' : '') }}" id="revenue_account_number" name="revenue_account_number" value="{{ $model->revenue_account_number }}">
                            @error('revenue_account_number')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="expense_account_number">Buchungskonto Ausgabe</label>
                            <input type="text" class="form-control {{ ($errors->has('expense_account_number') ? 'is-invalid' : '') }}" id="expense_account_number" name="expense_account_number" value="{{ $model->expense_account_number }}">
                            @error('expense_account_number')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" name="is_subscription" id="is_subscription" value="1" {{ $model->is_subscription ? 'checked="checked"' : '' }}>
                            <label class="form-check-label" for="is_subscription">Abo</label>
                        </div>

                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" name="is_flatrate" id="is_flatrate" value="1" {{ $model->is_flatrate ? 'checked="checked"' : '' }}>
                            <label class="form-check-label" for="is_flatrate">Kursflatrate</label>
                        </div>

                        <div class="form-group">
                            <label for="course_id">Verknüpfter Kurs</label>
                            <select class="form-control {{ ($errors->has('course_id') ? 'is-invalid' : '') }}" id="course_id" name="course_id">
                                <option value="">Kein Kurs</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id}}" {{ $model->course_id == $course->id ? 'selected="selected"' : '' }}>{{ $course->name }}</option>
                                @endforeach
                            </select>
                            @error('course_id')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-sm btn-primary">Speichern</button>
                    </div>
                </div>
            </div>
        </div>

    </form>

@endsection