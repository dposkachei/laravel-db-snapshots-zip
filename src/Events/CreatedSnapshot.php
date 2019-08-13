<?php

namespace SpatDposkacheiie\DbSnapshots\Events;

use Dposkachei\DbSnapshots\Snapshot;

class CreatedSnapshot
{
    /** @var \Dposkachei\DbSnapshots\Snapshot */
    public $snapshot;

    public function __construct(Snapshot $snapshot)
    {
        $this->snapshot = $snapshot;
    }
}
