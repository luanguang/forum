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
                        @include("profile.activities.{$record->type}",['activity' => $record])
                    @endforeach
                @endforeach

            </div>
        </div>
    </div>

@endsection