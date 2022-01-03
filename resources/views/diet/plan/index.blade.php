@extends('layouts.app')

@section('headline', \App\Models\Diet\Plans\Plan::label())

@section('buttons')
    <a href="{{ \App\Models\Diet\Meals\Meal::indexPath() }}" class="btn btn-secondary btn-sm mr-1">{{ \App\Models\Diet\Meals\Meal::label() }}</a>
    <a href="{{ \App\Models\Diet\Foods\Food::indexPath() }}" class="btn btn-secondary btn-sm">Nahrungsmittel</a>
@endsection

@section('content')

<diet-plan-table index-path="{{ \App\Models\Diet\Plans\Plan::indexPath() }}" :partners="{{ json_encode($partners) }}"></diet-plan-table>

@endsection