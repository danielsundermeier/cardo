@extends('layouts.app')

@section('content')

<h1>Arbeitszeit</h1>

<workingtime-table :selected-staff-id="{{ $selected_staff_id }}" :partners="{{ json_encode($partners) }}" :years="{{ json_encode($years) }}" :months="{{ json_encode($months) }}"></workingtime-table>

@endsection