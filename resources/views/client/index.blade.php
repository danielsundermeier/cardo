@extends('layouts.app')

@section('content')

<h1>Kunden</h1>

<partner-table uri="{{ $base_view_path }}"></partner-table>

@endsection