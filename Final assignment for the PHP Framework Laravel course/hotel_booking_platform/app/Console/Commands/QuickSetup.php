<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class QuickSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:quick-setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Quick setup: recreate database with all data in one command';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Quick Setup: Recreating database with all data...');

        Artisan::call('app:setup-database', ['--fresh' => true]);

        $this->line(Artisan::output());
        
        $this->info('✅ Quick setup completed! Your database is ready to use.');
        $this->info('');
        $this->info('🌐 You can now:');
        $this->info('   • Start the server: php artisan serve');
        $this->info('   • Run tests: php artisan test');
        $this->info('   • Access admin panel with default credentials');
        $this->info('   • Access manager panel with default credentials');
    }
}
