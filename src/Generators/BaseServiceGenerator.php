<?php

namespace LaravelApiCrudGenerator\Generators;

use LaravelApiCrudGenerator\Utils\Str;

class BaseServiceGenerator extends Generator
{
    public function __construct()
    {
        parent::__construct(self::TYPE_BASE_SERVICE, 'BaseService.php');
    }

    public function handle(): bool
    {
        return $this->saveFile([
            'namespace' => Str::pathToNamespace($this->path),
        ]);
    }
}
