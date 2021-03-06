@extends('layouts.app')

{{-- @section('header')
    <script src="https://www.google.com/recaptcha/api.js"></script>
@endsection --}}

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Create a New Thread</div>

                    <div class="panel-body">
                        <form method="post" action="/threads">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="channel_id">选择类型</label>
                                <select name="channel_id" id="channel_id" class="form-control" required>
                                    <option value="">请选择...</option>
                                    @foreach ($channels as $channel)
                                        <option value="{{ $channel->id }}" {{ old('channel_id') == $channel->id ? 'selected' : '' }}>
                                            {{ $channel->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="title">{{ trans('message.title') }}</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required> 
                            </div>

                            <div class="form-group">
                                <label for="body">内容</label>
                                <wysiwyg name="body" value="{{ old('body') }}"></wysiwyg>
                                {{--  <textarea name="body" id="body" class="form-control" rows="8" required>{{ old('body') }}</textarea>  --}}
                            </div>

                            {{--  <div class="form-group">
                                <div class="g-recaptcha" data-sitekey="6LeOE3gUAAAAACSDqdtVu2SL3j0sU4WzMfj1nPMS"></div>
                            </div>  --}}

                            <div class="form-group code">
                                <label>验证码</label>
                                <input class="tt-text" name="captcha">
                                <img src="{{captcha_src()}}" onclick="this.src='/captcha/default?'+Math.random()" id="captchaCode" alt="" class="captcha">
                                <a rel="nofollow" href="javascript:;" onclick="document.getElementById('captchaCode').src='captcha/default?'+Math.random()" class="reflash"></a>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">提交</button>
                            </div>

                            @if (count($errors))
                                <ul class="alert alert-danger">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection