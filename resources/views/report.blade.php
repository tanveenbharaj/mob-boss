@extends('layouts.master')

@section('content')

<div class="panel panel-default report-board">

    <div class="panel-heading">
        {{ $mob->name }} Report
    </div>

    <div class="panel-body">

        <div class='row bottom-buffer'>
            <div class="col-md-12">
                @if ($details->featureName)
                    <div>Current Feature: {{ $details->featureName  }}</div>
                @endif
            </div>
        </div>

        <div class='row'>
            <div class="col-md-4">
                <div class="panel panel-default">

                    <div class="panel-heading">
                        Crew
                    </div>

                    <div class="panel-body">

                        <ul class="list-group">
                            @foreach ($details->participants as $participant)
                                <li class="list-group-item {{ !$participant->contributor ? 'disabled' : '' }}">{{ $participant->name }} {!! !$participant->contributor ? '&nbsp <small>(inactive</small>)' : '' !!}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">

                    <div class="panel-heading">
                        Tasks
                    </div>

                    <div class="panel-body">
                        <ul class="list-group">
                            @foreach ($details->tasks as $task)
                                <li class="list-group-item @if ($task->completed) line-through @endif
                                    @if ( $task->severity == 1 )
                                        list-group-item-success
                                    @elseif ( $task->severity == 2 )
                                        list-group-item-info
                                    @elseif ( $task->severity == 3 )
                                        list-group-item-warning
                                    @elseif ( $task->severity == 4 )
                                        list-group-item-danger
                                    @endif">{{ $task->title }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">

                    <div class="panel-heading">
                        Notes
                    </div>

                    <div class="panel-body">
                        @foreach ($details->notes as $note)

                        <p class="blurbs @if ( $note->severity == 1 )
                                bg-success
                            @elseif ( $note->severity == 2 )
                                bg-info
                            @elseif ( $note->severity == 3 )
                                bg-warning
                            @elseif ( $note->severity == 4 )
                                bg-danger
                            @endif">{{ $note->body }}</p>

                        @endforeach
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>





@stop

