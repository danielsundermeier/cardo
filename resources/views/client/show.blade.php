@extends('layouts.app')

@section('content')

    <div class="d-flex mb-1">
        <h2 class="col mb-0"><a class="text-body" href="/{{ $base_view_path }}">Kunden</a><span class="d-none d-md-inline"> > {{ $model->name }}</span></h2>
        <div class="d-flex align-items-center">
            <a href="{{ route($base_view_path . '.edit', [$base_view_path => $model->id]) }}" class="btn btn-primary" title="Bearbeiten"><i class="fas fa-edit"></i></a>
            <a href="/{{ $base_view_path }}" class="btn btn-secondary ml-1">Übersicht</a>
            @if ($model->isDeletable())
                <form action="{{ route($base_view_path . '.destroy', [$base_view_path => $model->id]) }}" class="ml-1" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger" title="Löschen"><i class="fas fa-trash"></i></button>
                </form>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-6">

            @include('partner.show.address', ['model' => $model])

        </div>

        <div class="col-12 col-md-6">
            <div class="card mb-3">
                <div class="card-header">Kurse</div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Kurs</th>
                                <th>Zeit</th>
                                <th class="text-right">Offene Teilnahmen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($model->participants as $participant)
                                <tr>
                                    <td><a href="{{ $participant->course->path }}" target="_blank">{{ $participant->course->name }}</a></td>
                                    <td>{{ $participant->course->day_formatted }} {{ $participant->course->time_formatted }}</td>
                                    <td class="text-right">{{ $participant->open_participations_count }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2">Keine Kurse vorhanden</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card mb-3">
                <div class="card-header">Gesundheitsdaten</div>
                <div class="card-body">
                    <partner-healthdata-table :partner="{{ json_encode($model) }}"></partner-healthdata-table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card mb-3">
                <div class="card-header">Kommentare</div>
                <div class="card-body">
                    <comments :model="{{ json_encode($model) }}"></comments>
                </div>
            </div>
        </div>
    </div>

@endsection