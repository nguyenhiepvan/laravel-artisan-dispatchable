<?php

namespace Tests\TestClasses\Jobs\IntegrationTestJobs;

use Tests\TestClasses\Jobs\BaseTestJob;

class ArgumentWithoutTypeTestJob extends BaseTestJob
{
    public $argumentWithoutType;

    public function __construct(
        $argumentWithoutType
    ) {
        $this->argumentWithoutType = $argumentWithoutType;
    }
}
