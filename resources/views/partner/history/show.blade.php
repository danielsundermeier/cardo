@extends('layouts.app')

@section('content')

    <div class="d-flex mb-1">
        <h2 class="col mb-0 pl-0"><a class="text-body" href="{{ $model->partner->path }}">{{ $model->partner->name }}</a><span class="d-none d-md-inline"> > Anamnesebogen {{ $model->at_formatted }}</span></h2>
        <div class="d-flex align-items-center">
            <a href="{{ $model->partner->path }}" class="btn btn-secondary ml-1">Übersicht</a>
        </div>
    </div>

    <partner-history-edit :model="{{ json_encode($model) }}"></partner-history-edit>

@endsection