@extends('layouts.app')

@section('content')
    <div class="row mb-3">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">Meine Kurse</div>
                <div class="card-body">
                    @if (count($user->partner->courses))
                        <table class="table table-striped">
                            <tbody>
                                @foreach ($user->partner->courses as $course)
                                    <tr>
                                        <td><a href="{{ $course->path }}" target="_blank">{{ $course->name }}</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        Keine Daten vorhanden.
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div>Meine Aufgaben</div>
                    <a class="text-body" href="/task"><i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="card-body">
                    @if (count($categories))
                        @foreach ($categories as $category)
                            <h6>{{ $category->name }}</h6>
                            <table class="table table-striped table-sm">
                                <tbody>
                                    @foreach ($category->tasks as $task)
                                        <tr class="priority-{{ $task->priority }}">
                                            <td><a href="{{ $task->path }}" target="_blank">{{ $task->name }}</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endforeach
                    @else
                        Keine Daten vorhanden.
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">Nächste Geburtstage</div>
                <div class="card-body">
                    TODO
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">Meine Arbeitzeit</div>
                <div class="card-body">
                    TODO
                </div>
            </div>
        </div>
    </div>
@endsection
