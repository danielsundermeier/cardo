@extends('layouts.app')

@section('content')

<h1>Aufgaben</h1>
<div class="mb-1">
    <a href="{{ url('/task/category') }}" class="btn btn-secondary btn-sm">Kategorien</a>
</div>

<task-table :categories="{{ json_encode($categories) }}"></task-table>

@endsection