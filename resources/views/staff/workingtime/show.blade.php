@extends('layouts.workingtime')

@section('content')

    <div class="d-flex align-items-center flex-column">
        <h1>Arbeitszeit</h1>

        <partner-staff-workingtime-show :selected-staff-id="{{ $model->id }}"></partner-staff-workingtime-show>
    </div>

@endsection