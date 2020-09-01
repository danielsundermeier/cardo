@extends('layouts.app')

@section('content')

<h1>Lieferanten</h1>

<partner-table uri="{{ $base_view_path }}"></partner-table>

@endsection