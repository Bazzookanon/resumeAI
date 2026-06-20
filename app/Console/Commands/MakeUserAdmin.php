<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('user:make-admin {email}')]
#[Description('Grant admin panel access to a user by email')]
class MakeUserAdmin extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $user = User::where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error("No user found with email {$this->argument('email')}.");
            return self::FAILURE;
        }

        $user->forceFill(['is_admin' => true])->save();

        $this->info("{$user->email} is now an admin.");
        return self::SUCCESS;
    }
}
