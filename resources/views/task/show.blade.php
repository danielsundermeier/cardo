@extends('layouts.app')

@section('content')

    <div class="d-flex mb-1">
        <h2 class="col mb-0"><a class="text-body" href="/item">Aufgaben</a><span class="d-none d-md-inline"> > {{ $model->name }}</span></h2>
        <div class="d-flex align-items-center">
            @if ($model->is_completed)
                <form action="{{ $model->complete_path }}" class="mr-1" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-secondary" title="Löschen">Unerledigt</button>
                </form>
            @else
                <form action="{{ $model->complete_path }}" class="mr-1" method="POST">
                    @csrf
                    @method('PUT')

                    <button type="submit" class="btn btn-success" title="Löschen">Erledigt</button>
                </form>
            @endif
            <a href="{{ $model->edit_path }}" class="btn btn-primary" title="Bearbeiten"><i class="fas fa-edit"></i></a>
            <a href="/task" class="btn btn-secondary ml-1">Übersicht</a>
            @if ($model->isDeletable())
                <form action="{{ $model->path }}" class="ml-1" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger" title="Löschen">Löschen</button>
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
                        <div class="col-label"><b>Kategorie</b></div>
                        <div class="col-value">{{ $model->category->name }}</div>
                    </div>
                    <div>
                        {!! nl2br($model->description) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card mb-3">
                <form action="{{ $model->path }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-header">Anpassen</div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="user_id">Benutzer</label>
                            <select class="form-control {{ ($errors->has('user_id') ? 'is-invalid' : '') }}" id="user_id" name="user_id">
                                <option value="">Keine Zuordnung</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id}}" {{ $model->user_id == $user->id ? 'selected="selected"' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="priority">Priorität</label>
                            <select class="form-control {{ ($errors->has('priority') ? 'is-invalid' : '') }}" id="priority" name="priority">
                                @foreach($priorities as $key => $priority)
                                    <option value="{{ $key }}" {{ $model->priority == $key ? 'selected="selected"' : '' }}>{{ $priority }}</option>
                                @endforeach
                            </select>
                            @error('priority')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-secondary">Speichern</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card mb-3">
                <div class="card-header">Kommentare</div>
                <div class="card-body">
                    <comments :model="{{ json_encode($model) }}"></comments>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card mb-3">
                <form action="{{ $model->path }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-header">Notiz</div>
                    <div class="card-body">
                        <div class="form-group">
                            <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" rows="12">{!! $model->note !!}</textarea>
                            @error('note')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-secondary">Speichern</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection