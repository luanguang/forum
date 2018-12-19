@component('mail::message')
# Introduction

点击确认，让我知道你不是一个僵尸号！

@component('mail::button', ['url' => url('/register/confirm?token=' . $user->confirmation_token)])
确认
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
