<?php

namespace LaravelApiCrudGenerator\Generators;

use LaravelApiCrudGenerator\Utils\Str;
use Illuminate\Support\Facades\Schema;

class FormRequestGenerator extends Generator
{
    public function __construct()
    {
        parent::__construct(self::TYPE_FORM_REQUEST, 'FormRequest.php');
    }

    public function handle(): bool
    {
        return $this->saveFile([
            'namespace' => Str::pathToNamespace($this->path),
        ]);
    }
}
