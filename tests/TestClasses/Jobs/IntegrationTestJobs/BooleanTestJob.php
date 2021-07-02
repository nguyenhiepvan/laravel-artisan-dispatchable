<?php

namespace Tests\TestClasses\Jobs\IntegrationTestJobs;

use Tests\TestClasses\Jobs\BaseTestJob;

class BooleanTestJob extends BaseTestJob
{
    public bool $firstBoolean;
    public bool $secondBoolean;

    public function __construct(
        bool $firstBoolean,
        bool $secondBoolean
    ) {
        $this->secondBoolean = $secondBoolean;
        $this->firstBoolean  = $firstBoolean;
    }
}
