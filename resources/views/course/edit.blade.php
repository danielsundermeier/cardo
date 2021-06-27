@extends('layouts.app')

@section('content')

    <div class="d-flex mb-1">
        <h2 class="col mb-0"><a class="text-body" href="/courses">Kurse</a><span class="d-none d-md-inline"> > {{ $model->name }}</span></h2>
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
                            <label for="name">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Name" value="{{ $model->name }}">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="partner_id">Leiter</label>
                            <select class="form-control @error('partner_id') is-invalid @enderror" id="partner_id" name="partner_id">
                                @foreach ($partners as $partner)
                                    <option value="{{ $partner->id }}" {{ (($model->partner_id == $partner->id) ? 'selected="selected"' : '') }}>{{ $partner->name }}</option>
                                @endforeach
                            </select>
                            @error('partner_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="day">Tag</label>
                            <select class="form-control @error('day') is-invalid @enderror" id="day" name="day">
                                @foreach ($days as $day)
                                    <option value="{{ $loop->index }}" {{ (($model->day == $loop->index) ? 'selected="selected"' : '') }}> {{ $day }}</option>
                                @endforeach
                            </select>
                            @error('day')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="time_formatted">Zeit</label>
                            <input type="text" class="form-control @error('time_formatted') is-invalid @enderror" id="time_formatted" name="time_formatted" placeholder="time_formatted" value="{{ $model->time_formatted }}">
                            @error('time_formatted')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="duration_in_seconds">Dauer</label>
                            <select class="form-control @error('duration_in_seconds') is-invalid @enderror" id="duration_in_seconds" name="duration_in_seconds">
                                @foreach ($durations as $duration_in_seconds => $duration_formatted)
                                    <option value="{{ $duration_in_seconds }}" {{ (($model->duration_in_seconds == $duration_in_seconds) ? 'selected="selected"' : '') }}>{{ $duration_formatted }}</option>
                                @endforeach
                            </select>
                            @error('duration_in_seconds')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="description">Beschreibung</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="12">{!! $model->description !!}</textarea>
                            @error('description')
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