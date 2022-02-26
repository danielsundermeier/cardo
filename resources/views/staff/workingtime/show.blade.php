@extends('layouts.workingtime')

@section('content')

    <div class="d-flex align-items-center flex-column">
        <h1>Arbeitszeit</h1>

        <h2>{{ $model->name }}</h2>

        <partner-staff-workingtime-show :selected-staff-id="{{ $model->id }}" :model="{{ json_encode($model) }}"></partner-staff-workingtime-show>
    </div>

@endsection