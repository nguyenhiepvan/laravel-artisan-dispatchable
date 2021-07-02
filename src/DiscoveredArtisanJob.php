<?php

namespace Spatie\ArtisanDispatchable;

class DiscoveredArtisanJob
{
    public string $jobClassName;
    public string $commandDescription;
    public string $commandSignature;

    public function __construct(
        string $jobClassName,
        string $commandSignature,
        string $commandDescription
    ) {
        $this->commandSignature   = $commandSignature;
        $this->commandDescription = $commandDescription;
        $this->jobClassName       = $jobClassName;
    }

    public function toArray(): array
    {
        return [
            'jobClassName' => $this->jobClassName,
            'commandSignature' => $this->commandSignature,
            'commandDescription' => $this->commandDescription,
        ];
    }
}
