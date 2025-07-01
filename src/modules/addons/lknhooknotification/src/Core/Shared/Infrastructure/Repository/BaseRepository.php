<?php

namespace Lkn\HookNotification\Core\Shared\Infrastructure\Repository;

use WHMCS\Database\Capsule;

/**
 * This should be inherited by all infrastructure repositories.
 */
abstract class BaseRepository
{
    /**
     * Use this to mount queries to the database.
     *
     */
    public Capsule $query;

    public function __construct()
    {
        $this->query = Capsule::getInstance();
    }
}
