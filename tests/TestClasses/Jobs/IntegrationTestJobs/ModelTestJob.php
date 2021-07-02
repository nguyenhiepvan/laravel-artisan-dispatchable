<?php

namespace Tests\TestClasses\Jobs\IntegrationTestJobs;

use Tests\TestClasses\Jobs\BaseTestJob;
use Tests\TestClasses\Models\TestModel;

class ModelTestJob extends BaseTestJob
{
    public TestModel $testModel;

    public function __construct(TestModel $testModel)
    {
        $this->testModel = $testModel;
    }
}
