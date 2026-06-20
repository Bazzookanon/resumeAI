<?php

namespace App\Listeners;

use App\Models\LoginLog;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;

class LogSuccessfulLogin
{
    public function __construct(protected Request $request) {}

    public function handle(Login $event): void
    {
        LoginLog::create([
            'user_id'    => $event->user->id,
            'ip_address' => $this->request->ip(),
            'user_agent' => $this->request->userAgent(),
        ]);

        $event->user->forceFill(['last_seen_at' => now()])->save();
    }
}
