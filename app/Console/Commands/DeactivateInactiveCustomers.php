<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class DeactivateInactiveCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:deactivate-inactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nonaktifkan akun customer yang tidak pernah login selama 30 hari';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cutoff = now()->subDays(30);

        $count = User::where('role', 'customer')
            ->where('is_active', true)
            ->where(function ($query) use ($cutoff) {
                $query->where('last_login_at', '<', $cutoff)
                    ->orWhere(function ($q) use ($cutoff) {
                        $q->whereNull('last_login_at')->where('created_at', '<', $cutoff);
                    });
            })
            ->update(['is_active' => false]);

        $this->info("{$count} akun customer tidak aktif telah dinonaktifkan.");
    }
}
