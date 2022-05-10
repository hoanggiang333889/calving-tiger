<?php

namespace LaravelApiCrudGenerator\Generators;

use LaravelApiCrudGenerator\Entities\Table;
use LaravelApiCrudGenerator\Utils\Str;
use Illuminate\Support\Facades\Schema;

class ModelGenerator extends Generator
{
    /**
     * __construct
     *
     * @param  mixed $table
     * @return void
     */
    public function __construct(String $table)
    {
        $this->tableName = $table;
        $fileName = Str::singularStudly($table) . '.php';
        $model = Str::singularStudly($table);
        parent::__construct(self::TYPE_MODEL, $fileName, $table, $model);
    }

    public function handle(): bool
    {
        $this->fields = Schema::getColumnListing($this->table);

        return $this->saveFile([
            'table' => $this->table,
            'model' => $this->model,
            'fields' => $this->fields,
            'namespace' => Str::pathToNamespace($this->path),
            'namespaceRepository' => Str::pathToNamespace(self::getPath(self::TYPE_REPOSITORY, $this->table)),
            'namespaceModel' => Str::pathToNamespace(self::getPath(self::TYPE_MODEL, $this->table)),
            'namespaceService' => Str::pathToNamespace(self::getPath(self::TYPE_SERVICE, $this->table)),
            'namespaceResource' => Str::pathToNamespace(self::getPath(self::TYPE_RESOURCE, $this->table)),
            'namespaceCreateRequest' => Str::pathToNamespace(self::getPath(self::TYPE_CREATE_REQUEST, $this->table)),
            'namespaceUpdateRequest' => Str::pathToNamespace(self::getPath(self::TYPE_UPDATE_REQUEST, $this->table)),
        ]);
    }
}
