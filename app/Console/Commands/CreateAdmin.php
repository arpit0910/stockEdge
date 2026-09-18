<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('stockedge:admin {email : Email of an existing registered user}')]
#[Description('Grant admin access to an existing SharesRise account')]
class CreateAdmin extends Command
{
    public function handle(): int
    {
        $user = User::where('email', $this->argument('email'))->first();
        if (! $user) {
            $this->error('Register an account on the website first.');

            return self::FAILURE;
        }
        $user->forceFill(['is_admin' => true])->save();
        $this->info('Admin access granted. Sign in and visit /admin.');

        return self::SUCCESS;
    }
}
