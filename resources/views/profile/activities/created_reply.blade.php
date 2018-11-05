@component('profile.activities.activity')
    @slot('heading')
        {{ $profileUser->name }} 发表了回复
        <a href="{{ $activity->subject->thread->path() }}">{{ $activity->subject->thread->title }}</a>
    @endslot

    @slot('body')
        {!! $activity->subject->body !!}
    @endslot
@endcomponent
