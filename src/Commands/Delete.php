<?php

namespace Dposkachei\DbSnapshots\Commands;

use Illuminate\Console\Command;
use Dposkachei\DbSnapshots\SnapshotRepository;
use Dposkachei\DbSnapshots\Commands\Concerns\AsksForSnapshotName;

class Delete extends Command
{
    use AsksForSnapshotName;

    protected $signature = 'snapshot:delete {name?}';

    protected $description = 'Delete a snapshot.';

    public function handle()
    {
        $name = $this->argument('name') ?: $this->askForSnapshotName();

        $snapshot = app(SnapshotRepository::class)->findByName($name);

        if (! $snapshot) {
            $this->warn("Snapshot `{$name}` does not exist!");

            return;
        }

        $snapshot->delete();

        $this->info("Snapshot `{$snapshot->name}` deleted!");
    }
}
