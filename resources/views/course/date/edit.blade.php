@extends('layouts.app')

@section('content')

    <div class="d-flex mb-1">
        <h2 class="col mb-0"><a class="text-body" href="/courses">Kurse</a><span class="d-none d-md-inline"> > <a href="{{ $parent->path }}" class="text-body">{{ $parent->name }}</a> > {{ $model->at_formatted }}</span></h2>
        <div class="d-flex align-items-center">
            <a href="{{ $model->path }}" class="btn btn-secondary ml-1">Übersicht</a>
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
                            <label for="at_formatted">Datum</label>
                            <input type="text" class="form-control @error('at_formatted') is-invalid @enderror" id="at_formatted" name="at_formatted" placeholder="dd.mm.yyyy" value="{{ $model->at_formatted }}">
                            @error('at_formatted')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="staff_id">Leiter</label>
                            <select class="form-control @error('staff_id') is-invalid @enderror" id="staff_id" name="staff_id">
                                @foreach ($partners as $partner)
                                    <option value="{{ $partner->id }}" {{ (($model->staff_id == $partner->id) ? 'selected="selected"' : '') }}>{{ $partner->name }}</option>
                                @endforeach
                            </select>
                            @error('staff_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Speichern</button>
                    </div>
                </div>
            </div>
        </div>

    </form>

@endsection