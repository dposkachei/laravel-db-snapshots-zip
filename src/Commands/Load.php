<?php

namespace Dposkachei\DbSnapshots\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Dposkachei\DbSnapshots\Commands\Concerns\AsksForSnapshotTable;
use Dposkachei\DbSnapshots\SnapshotRepository;
use Dposkachei\DbSnapshots\Commands\Concerns\AsksForSnapshotName;

class Load extends Command
{
    use AsksForSnapshotName;
    use AsksForSnapshotTable;
    use ConfirmableTrait;

    protected $signature = 'snapshot:load {name?} {table?} --disk';

    protected $description = 'Load up a snapshots.';

    public function handle()
    {
        $snapShots = app(SnapshotRepository::class)->getAll();

        if ($snapShots->isEmpty()) {
            $this->warn('No snapshots found. Run `snapshot:create` first to create snapshots.');

            return;
        }

        if (! $this->confirmToProceed()) {
            return;
        }

        $name = $this->argument('name') ?: $this->askForSnapshotName();

        //$table = $this->argument('table') ?: $this->askForSnapshotTable();

        $snapshot = app(SnapshotRepository::class)->findByName($name);

        if (! $snapshot) {
            $this->warn("Snapshot `{$name}` does not exist!");

            return;
        }

        $snapshot->load();

        $this->info("Snapshot `{$name}` loaded!");
    }
}
