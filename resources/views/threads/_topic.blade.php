{{-- Edit --}}
<div class="panel panel-default" v-if="editing">
    <div class="panel-heading">
        <div class="level">
            <input type="text" class="form-control" v-model="form.title">
        </div>
    </div>

    <div class="panel-body">
        <div class="form-group">
            <wysiwyg v-model="form.body" :value="form.body"></wysiwyg>
            {{--  <textarea class="form-control" rows="10" v-model="form.body"></textarea>  --}}
        </div>
    </div>

    <div class="panel-footer">
        <div class="level">
            <button class="btn btn-primary btn-xs level-item" @click="update">更新</button>
            <button class="btn btn-xs level-item" @click="resetForm">取消</button>

            @can('update',$thread)
                <form action="{{ $thread->path() }}" method="POST" class="ml-a">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}

                    <button type="submit" class="btn btn-link">删除文章</button>
                </form>
            @endcan
        </div>
    </div>
</div>

{{-- View --}}
<div class="panel panel-default" v-else>
    <div class="panel-heading">
        <div class="level">
            <img src="/storage/{{ $thread->creator->avatar_path }}" alt="{{ $thread->creator->name }}" width="25" height="25" class="mr-1">

            <span class="flex">
                <a href="{{ route('profile',$thread->creator) }}">{{ $thread->creator->name }}</a> posted: <span v-text="title"></span>
            </span>
        </div>
    </div>

    <div class="panel-body" v-html="body">
    </div>

    <div class="panel-footer" v-if="authorize('owns', thread)">
        <button class="btn btn-xs" @click="editing = true">{{ trans('message.edit') }}</button>
    </div>
</div>