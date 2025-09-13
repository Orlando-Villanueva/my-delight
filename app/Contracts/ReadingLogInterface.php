<?php

namespace App\Contracts;

use Carbon\Carbon;

interface ReadingLogInterface
{
    /**
     * Get the date when the reading was performed.
     */
    public function getDateRead(): ?string;

    /**
     * Get the timestamp when the reading log was created.
     */
    public function getCreatedAt(): Carbon;
}
