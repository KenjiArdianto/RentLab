<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Registered;
use App\Listeners\LogUserRegistered;
use App\Listeners\LogUserLogin;
use App\Listeners\LogUserLogout;
use App\Listeners\LogUserFailedLogin;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        SocialiteWasCalled::class => [
            'SocialiteProviders\\Google\\GoogleExtendSocialite@handle',
        ],
        Login::class => [
            LogUserLogin::class,
        ],
        Logout::class => [
            LogUserLogout::class,
        ],
        Failed::class => [
            LogUserFailedLogin::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
