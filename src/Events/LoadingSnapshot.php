<?php

namespace Dposkachei\DbSnapshots\Events;

use Dposkachei\DbSnapshots\Snapshot;

class LoadingSnapshot
{
    /** @var \Dposkachei\DbSnapshots\Snapshot */
    public $snapshot;

    public function __construct(Snapshot $snapshot)
    {
        $this->snapshot = $snapshot;
    }
}
