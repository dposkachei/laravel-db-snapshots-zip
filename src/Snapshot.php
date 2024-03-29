<?php

namespace Dposkachei\DbSnapshots;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\MigrateFresh\TableDropperFactory;
use Dposkachei\DbSnapshots\Events\LoadedSnapshot;
use Dposkachei\DbSnapshots\Events\DeletedSnapshot;
use Dposkachei\DbSnapshots\Events\LoadingSnapshot;
use Dposkachei\DbSnapshots\Events\DeletingSnapshot;
use Illuminate\Filesystem\FilesystemAdapter as Disk;

class Snapshot
{
    /** @var \Illuminate\Filesystem\FilesystemAdapter */
    public $disk;

    /** @var string */
    public $fileName;

    /** @var string */
    public $name;

    public function __construct(Disk $disk, string $fileName)
    {
        $this->disk = $disk;

        $this->fileName = $fileName;

        $this->name = pathinfo($fileName, PATHINFO_FILENAME);
    }

    public function file()
    {
        return config('filesystems.disks.snapshots.root') . '/' . $this->name . '.zip';
    }

    public function load($table = '')
    {
        event(new LoadingSnapshot($this));

        $this->dropAllCurrentTables($table);

        $dbDumpContents = $this->disk->get($this->fileName);

        DB::unprepared($dbDumpContents);

        event(new LoadedSnapshot($this));
    }

    public function delete()
    {
        event(new DeletingSnapshot($this));

        $this->disk->delete($this->fileName);

        event(new DeletedSnapshot($this->fileName, $this->disk));
    }

    public function size(): int
    {
        return $this->disk->size($this->fileName);
    }

    public function createdAt(): Carbon
    {
        return Carbon::createFromTimestamp($this->disk->lastModified($this->fileName));
    }

    protected function dropAllCurrentTables($table = '')
    {
        $tableDropper = TableDropperFactory::create(DB::getDriverName());

        if ($table === '') {
            $tableDropper->dropAllTables();
        }

        DB::reconnect();
    }
}
