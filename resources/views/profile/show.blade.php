@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2">
                <div class="page-header">
                    <avatar-form :user="{{ $profileUser }}"></avatar-form>
                </div>
                <span id="signIn">
                    <button onclick="signIn()">签到</button>
                </span>

                @forelse($activities as $date => $activity)
                    <h3 class="page-header">{{ $date }}</h3>

                    @foreach($activity as $record)
                        @if (view()->exists("profile.activities.{$record->type}"))
                            @include("profile.activities.{$record->type}",['activity' => $record])
                        @endif
                    @endforeach

                    @empty
                        <p>There is no activity for this user yet.</p>
                @endforelse

            </div>
        </div>
    </div>

    <script>
        function signIn()
        {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    let info = document.getElementById('signIn');
                    info.innerHTML = '签到成功';
                }
            }
            xhr.open('POST', '/profile/' + '{{ $profileUser->name }}' + '/signIn', true);
            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            xhr.send();
        }

    </script>

@endsection