@extends('layouts.app')

@section('content')

<h1>Produkte</h1>
<div class="mb-1">
    <a href="{{ url('/unit') }}" class="btn btn-secondary btn-sm">Einheiten</a>
</div>

<item-table></item-table>

@endsection