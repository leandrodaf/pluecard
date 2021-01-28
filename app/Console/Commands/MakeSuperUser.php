<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeSuperUser extends Command
{
    protected $signature = 'grants:admin {user} {--RO|role=Administrator} {--R|reset}';

    protected $description = 'Grants super user permission';

    public function handle()
    {
        $user = User::where('id', $this->argument('user'))->firstOrFail();

        if ($this->option('reset')) {
            $user->removeRole('Administrator');
            $this->info('Role reseted.');

            return;
        }

        $user->assignRole('Administrator');
        $this->info('Role is applicated.');
    }
}
