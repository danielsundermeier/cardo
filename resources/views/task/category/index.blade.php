@extends('layouts.app')

@section('content')

<div class="d-flex mb-1">
    <h1 class="col pl-0">Aufgaben > Kategorien</h1>
    <div class="d-flex align-items-center">
        <a href="/task" class="btn btn-secondary ml-1">Übersicht</a>
    </div>
</div>

<task-category-table></task-category-table>

@endsection