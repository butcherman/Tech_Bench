<?php

namespace App\Console\Commands\Maint;

use App\Enums\ContainerList;
use App\Services\Maintenance\DockerControlService;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;

class RebootTechBenchCommand extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reboot
                                { container? : Name of Container to Reboot}
                                {--force : Force the operation to run without confirmation}
                                {--all : Reboot All Containers}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reboot Tech Bench Services';

    /**
     * Execute the console command.
     */
    public function handle(DockerControlService $svc)
    {
        $container = null;

        /**
         * Reboot all Containers
         */
        if ($this->option('all')) {
            if (!$this->option('force')) {
                if (!confirm(label: 'Are you sure you want to reboot?',)) {
                    return;
                };
            }

            $this->info('Logging you out and Rebooting Tech Bench');
            $svc->rebootAllContainers();
        }

        /**
         * If no container was given, allow user to select from available list
         */
        if ($this->argument('container')) {
            $container = ContainerList::tryFrom($this->argument('container'));
        }

        /**
         * Reboot a single container
         */
        if (is_null($container)) {
            $selection = select(
                label: 'Select a Service to Reboot',
                options: array_column(ContainerList::cases(), 'value'),
            );

            $container = ContainerList::from($selection);

            if (!$this->option('force')) {
                if (!confirm(label: 'Are you sure you want to reboot?',)) {
                    return;
                };
            }

            $this->info('Rebooting ' . $container->value);
            $svc->rebootContainer($container);
        }

        $this->info('Reboot Command Sent.  Please allow time for reboot to complete.');
    }
}
