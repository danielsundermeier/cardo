@extends('layouts.app')

@section('content')

    <div class="d-flex mb-1">
        <h2 class="col mb-0"><a class="text-body" href="/{{ $base_view_path }}">Personal</a><span class="d-none d-md-inline"> > {{ $model->name }}</span></h2>
        <div class="d-flex align-items-center">
            <a href="{{ route($base_view_path . '.show', [$base_view_path => $model->id]) }}" class="btn btn-secondary ml-1">Übersicht</a>
        </div>
    </div>
    <form action="{{ route($base_view_path . '.show', [$base_view_path => $model->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col">
                @include('partner.edit.address', ['model' => $model])
            </div>

            <div class="col">
                <div class="card mb-5">
                    <div class="card-header">Verknüpfter User</div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="user_id">User</label>
                            <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id">
                                <option value="" {{ (is_null($model->user_id) ? 'selected="selected"' : '') }}>Kein User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ (($model->user_id == $user->id) ? 'selected="selected"' : '') }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                @include('partner.edit.type', ['model' => $model])

                @include('partner.edit.bank', ['model' => $model])
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary">Speichern</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection