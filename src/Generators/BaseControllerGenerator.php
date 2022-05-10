<?php

namespace LaravelApiCrudGenerator\Generators;

use LaravelApiCrudGenerator\Utils\Str;

class BaseControllerGenerator extends Generator
{
    public function __construct()
    {
        parent::__construct(self::TYPE_BASE_CONTROLLER, 'Controller.php');
    }

    public function handle(): bool
    {
        return $this->saveFile([
            'namespace' => Str::pathToNamespace($this->path),
        ]);
    }
}
