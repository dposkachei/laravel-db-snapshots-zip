<?php

namespace Dposkachei\DbSnapshots\Commands\Concerns;

use Dposkachei\DbSnapshots\Snapshot;
use Dposkachei\DbSnapshots\SnapshotRepository;

trait AsksForSnapshotName
{
    public function askForSnapshotName(): string
    {
        $snapShots = app(SnapshotRepository::class)->getAll();

        $names = $snapShots->map(function (Snapshot $snapshot) {
            return $snapshot->name;
        })->values()->toArray();

        return $this->choice('Which snapshot?', $names, 0);
    }
}
