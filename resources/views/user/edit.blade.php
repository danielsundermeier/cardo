@extends('layouts.app')

@section('content')

    <div class="d-flex mb-1">
        <h2 class="col mb-0 pl-0"><a class="text-body" href="/user">Benutzer</a><span class="d-none d-md-inline"> > {{ $model->name }}</span></h2>
        <div class="d-flex align-items-center">
            <a href="/user" class="btn btn-secondary ml-1">Übersicht</a>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-lg-6">

            <form action="{{ $model->path }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card mb-3">
                    <div class="card-header">Anpassen</div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input id="name" type="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $model->name }}" required>
                            @error('name')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">E-Mail</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $model->email }}" required>
                            @error('email')
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

            </form>

        </div>
        <div class="col-12 col-lg-6">

            @if (Auth::user()->id == $model->id)
                <form action="{{ $model->path }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card mb-3">
                        <div class="card-header">Passwort ändern</div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="password">Passwort</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                @error('password')
                                    <div class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Passwort bestätigen</label>
                                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Speichern</button>
                        </div>
                    </div>

                </form>
            @endif

        </div>

@endsection