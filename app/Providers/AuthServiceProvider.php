<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Policies\ThreadPolicy;
use App\Model\Thread;
use App\Policies\ReplyPolicy;
use App\Model\Reply;
use App\Model\User;
use App\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Thread::class => ThreadPolicy::class,
        Reply::class  => ReplyPolicy::class,
        User::class   => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //用户授权
        Gate::before(function ($user) {
            if ($user->name === '曹婉婉') {
                return true;
            }
        });
    }
}
