<reply :attributes="{{ $reply }}" inline-template v-cloak>
    <div id="reply-{{ $reply->id }}" class="panel panel-default">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a href="{{ route('profile', $reply->owner) }}">{{ $reply->owner->name }}</a>
                    回复于 {{ $reply->created_at->diffForHumans() }}
                </h5>
                @if(Auth::check())
                    <div>
                        <favorite :reply="{{ $reply }}"></favorite>
                        {{-- <form method="POST" action="/replies/{{ $reply->id }}/favorite">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-default" {{ $reply->isFavorited() ? 'disabled' : '' }}>
                                {{ $reply->favorites_count }} {{ str_plural('Favorite', $reply->favorites()->count()) }}
                            </button>
                        </form> --}}
                    </div>
                @endif
            </div>
        </div>

        <div class="panel-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>

                <button class="btn btn-xs btn-primary" @click="update">{{ trans('message.update') }}</button>
                <button class="btn btn-xs btn-link" @click="editing = false">{{ trans('message.cancel') }}</button>
            </div>

            <div v-else v-text="body"> </div>

            {{--  <div v-else>
                {{ $reply->body }}
            </div>  --}}
        </div>

        @can('update', $reply)
            <div class="panel-footer level">
                <button class="btn btn-xs mr-1" @click="editing = true">{{ trans('message.edit') }}</button>
                <button class="btn btn-danger btn-xs mr-1" @click="destroy">{{ trans('message.delete') }}</button>
            </div>
        @endcan
    </div>
</reply>