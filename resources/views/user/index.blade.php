@extends('layouts.app')

@section('content')

<h1>Benutzer</h1>
<div class="alert alert-secondary" role="alert">
    Standard Passwort für neue Benutzer: "<b>{{ $default_password }}</b>"
</div>
<user-table></user-table>

@endsection