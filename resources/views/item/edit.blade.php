@extends('layouts.app')

@section('content')

    <div class="d-flex mb-1">
        <h2 class="col mb-0"><a class="text-body" href="/item">Produkte</a><span class="d-none d-md-inline"> > {{ $model->name }}</span></h2>
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
                            <label for="unit_id">Einheit</label>
                            <select class="form-control {{ ($errors->has('unit_id') ? 'is-invalid' : '') }}" id="unit_id" name="unit_id">
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id}}" {{ $model->unit_id == $unit->id ? 'selected="selected"' : '' }}>{{ $unit->name }} ({{ $unit->abbreviation }})</option>
                                @endforeach
                            </select>
                            @error('unit_id'))
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="course_id">Verknüpfter Kurs</label>
                            <select class="form-control {{ ($errors->has('course_id') ? 'is-invalid' : '') }}" id="course_id" name="course_id">
                                <option value="">Kein Kurs</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id}}" {{ $model->course_id == $course->id ? 'selected="selected"' : '' }}>{{ $course->name }}</option>
                                @endforeach
                            </select>
                            @error('course_id'))
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
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