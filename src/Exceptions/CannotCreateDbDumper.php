<?php

namespace Dposkachei\DbSnapshots\Exceptions;

use Exception;

class CannotCreateDbDumper extends Exception
{
    public static function unsupportedDriver(string $driver): CannotCreateDbDumper
    {
        return new static("Cannot create a dumper for db driver `{$driver}`. Use `mysql`, `pgsql` or `sqlite`.");
    }

    public static function connectionDoesNotExist(string $connectionName): CannotCreateDbDumper
    {
        return new static("Cannot create a dumper. Connection `{$connectionName}` does not exist.");
    }
}
