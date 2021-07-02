<?php

namespace Tests\TestClasses\Jobs\IntegrationTestJobs;

use Tests\TestClasses\Jobs\BaseTestJob;

class IntegerTestJob extends BaseTestJob
{
    public int $myInteger;

    public function __construct(
        int $myInteger
    ) {
        $this->myInteger = $myInteger;
    }
}
