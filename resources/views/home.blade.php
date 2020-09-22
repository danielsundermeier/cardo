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
                <div class="card-header">Meine Aufgaben</div>
                <div class="card-body">
                    @if (count($user->tasks))
                        <table class="table table-striped">
                            <tbody>
                                @foreach ($user->tasks as $task)
                                    <tr>
                                        <td><a href="{{ $task->path }}" target="_blank">{{ $task->name }}</a></td>
                                        <td>{{ $task->category->name }}</td>
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
