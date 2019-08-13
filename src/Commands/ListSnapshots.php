<?php

namespace Dposkachei\DbSnapshots\Commands;

use Illuminate\Console\Command;
use Dposkachei\DbSnapshots\Snapshot;
use Dposkachei\DbSnapshots\Helpers\Format;
use Dposkachei\DbSnapshots\SnapshotRepository;

class ListSnapshots extends Command
{
    protected $signature = 'snapshot:list';

    protected $description = 'List all the snapshots.';

    public function handle()
    {
        $snapshots = app(SnapshotRepository::class)->getAll();

        if ($snapshots->isEmpty()) {
            $this->warn('No snapshots found. Run `snapshot:create` to create one.');

            return;
        }

        $rows = $snapshots->map(function (Snapshot $snapshot) {
            return [
                $snapshot->name,
                $snapshot->createdAt()->format('Y-m-d H:i:s'),
                Format::humanReadableSize($snapshot->size()),
            ];
        });

        $this->table(['Name', 'Created at', 'Size'], $rows);
    }
}
