<?php

namespace App\Console\Commands\Maint;

use App\Services\Maintenance\DockerControlService;
use Illuminate\Console\Command;

use function Laravel\Prompts\confirm;

class AppRebootCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reboot {--f|force : Reboot without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reboot all Tech Bench Docker Containers';

    /**
     * Execute the console command.
     */
    public function handle(DockerControlService $svc)
    {
        if (! $this->option('force')) {
            $continue = confirm(
                label: 'Are you sure you want to reboot?',
                default: false,
            );

            if (! $continue) {
                $this->info('Canceling Reboot');

                return 1;
            }
        }

        $this->info('Rebooting Tech Bench, please wait.');
        $this->info('You will be logged out of this session.');

        $svc->rebootAllContainers();
    }
}
