@extends('layouts.app')

@section('content')

<h1>Personal</h1>
<div class="mb-1">
    <a href="{{ url('/user') }}" class="btn btn-secondary btn-sm">Benutzer</a>
</div>

<partner-table uri="{{ $base_view_path }}"></partner-table>

@endsection