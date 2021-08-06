@extends('layouts.app')

@section('content')

<div class="d-flex mb-1">
    <h2 class="col mb-0"><a class="text-body" href="/course">Kurse</a><span class="d-none d-md-inline"> > {{ $model->name }}</span> > Teilnehmer</h2>
    <div class="d-flex align-items-center">
        <a href="{{ $model->path }}" class="btn btn-sm btn-secondary ml-1">Übersicht</a>
    </div>
</div>

<courses-participation-table :model="{{ json_encode($model) }}"></courses-participation-table>

@endsection