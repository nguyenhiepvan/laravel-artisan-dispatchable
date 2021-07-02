<?php

namespace Tests\TestClasses\Jobs\CacheTestJobs;

use Tests\TestClasses\Jobs\BaseTestJob;

class StringTestJob extends BaseTestJob
{
    public string $myString;
    public string $anotherString;

    public function __construct(
        string $myString,
        string $anotherString
    ) {
        $this->anotherString = $anotherString;
        $this->myString      = $myString;
    }
}
