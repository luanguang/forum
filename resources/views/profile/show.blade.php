@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2">
                <div class="page-header">
                    <h1>
                        {{ $profileUser->name }}
                        <small>注册于{{ $profileUser->created_at->diffForHumans() }}</small>
                    </h1>
                </div>

                @foreach($activities as $date => $activity)
                    <h3 class="page-header">{{ $date }}</h3>

                    @foreach($activity as $record)
                        @if (view()->exists("profile.activities.{$record->type}"))
                            @include("profile.activities.{$record->type}",['activity' => $record])
                        @endif
                    @endforeach
                @endforeach

            </div>
        </div>
    </div>

@endsection