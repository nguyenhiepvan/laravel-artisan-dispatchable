<?php

namespace Spatie\ArtisanDispatchable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionParameter;
use Spatie\ArtisanDispatchable\Exceptions\ModelNotFound;
use Spatie\ArtisanDispatchable\Exceptions\RequiredOptionMissing;

class ArtisanJob
{
    protected string $jobClassName;

    public function __construct(string $jobClassName)
    {
        $this->jobClassName = $jobClassName;
    }

    public function getFullCommand(): string
    {
        return "{$this->getCommandName()} {$this->getOptionString()}";
    }

    protected function getCommandName(): string
    {
        if ($name = $this->getDefaultForProperty('artisanName')) {
            return $name;
        }

        $shortClassName = class_basename($this->jobClassName);

        $prefix  = config('artisan-dispatchable.command_name_prefix');
        $command = Str::of($shortClassName)->kebab()->beforeLast('-job');

        return $prefix
            ? "{$prefix}:{$command}"
            : $command;
    }

    public function getCommandDescription(): string
    {
        return $this->getDefaultForProperty('artisanDescription') ?? "Execute job {$this->jobClassName}";
    }

    protected function getOptionString(): string
    {
        $instance   = (new ReflectionClass($this->jobClassName))
            ->getConstructor();
        $parameters = $instance ? $instance->getParameters() ?? [] : [];

        return collect($parameters)
            ->map(fn(ReflectionParameter $parameter) => $parameter->name)
            ->map(fn(string $argumentName) => '{--' . Str::camel($argumentName) . '=}')
            ->add('{--queued}')
            ->implode(' ');
    }

    public function handleCommand(ClosureCommand $command): void
    {
        $parameters = $this->constructorValues($command);

        $job = new $this->jobClassName(...$parameters);

        $command->option('queued')
            ? dispatch($job)
            : dispatch_sync($job);
    }

    protected function constructorValues(ClosureCommand $command): array
    {
        $instance   = (new ReflectionClass($this->jobClassName))
            ->getConstructor();
        $parameters = $instance ? $instance->getParameters() ?? [] : [];

        if (is_null(($parameters))) {
            return [];
        }

        return collect($parameters)
            ->map(function (ReflectionParameter $parameter) use ($command) {
                $parameterName = $parameter->getName();

                $value = $command->option($parameterName);

                if (is_null($value)) {
                    throw RequiredOptionMissing::make($this->getCommandName(), $parameterName);
                }

                $type          = $parameter->getType();
                $parameterType = $type ? $type->getName() ?? "" : "";

                if (is_a($parameterType, Model::class, true)) {
                    $model = $parameterType::find($value);

                    if (is_null($model)) {
                        throw ModelNotFound::make($this->getCommandName(), $parameterName, $value);
                    }

                    $value = $model;
                }

                return $value;
            })
            ->all();
    }

    /**
     * @param string $name
     * @return mixed|null
     * @throws \ReflectionException
     */
    protected function getDefaultForProperty(string $name)
    {
        $reflectionClass = new ReflectionClass($this->jobClassName);

        $defaultProperties = $reflectionClass->getDefaultProperties();

        return $defaultProperties[$name] ?? null;
    }
}
