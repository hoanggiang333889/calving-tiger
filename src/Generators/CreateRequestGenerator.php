<?php

namespace LaravelApiCrudGenerator\Generators;

use LaravelApiCrudGenerator\Entities\Table;
use LaravelApiCrudGenerator\Utils\Str;
use Illuminate\Support\Facades\Schema;

class CreateRequestGenerator extends Generator
{
    protected String $tableName;
    /**
     * fields
     *
     * @var array
     */
    protected $fields = [];

    /**
     * __construct
     *
     * @param  mixed $table
     * @return void
     */
    public function __construct(String $table)
    {
        $fileName = 'Create' . Str::singularStudly($table) . 'Request.php';
        $model = Str::singularStudly($table);
        parent::__construct(self::TYPE_CREATE_REQUEST, $fileName, $table, $model);
    }

    public function handle(): bool
    {
        $fieldRules = [];
        $getId = false;
        $this->fields = Schema::getColumnListing($this->table);

        foreach ($this->fields as $field) {
            $fieldRules[$field][] = 'required';
            $type = Schema::getColumnType($this->table, $field);
            switch ($type) {
                case 'integer':
                    $fieldRules[$field][] = 'numeric';
                    break;
                case 'date':
                    $fieldRules[$field][] = 'date_format:Y-m-d';
                    break;
                case 'datetime':
                    $fieldRules[$field][] = 'date_format:Y-m-d H:i:s';
                    break;
            }

            // if ($field->foreignKeyTable) {
            //     $fieldRules[$field->name][] = 'exists:' . $field->foreignKeyTable . ',id';
            // }

            // if ($field->unique) {
            //     $fieldRules[$field->name][] = 'unique:' . $this->table->name . ',' . $field->name . ',$id';
            //     $getId = true;
            // }
        }

        return $this->saveFile([
            'table' => $this->table,
            'model' => $this->model,
            'fields' => $this->fields,
            'fieldRules' => $fieldRules,
            'getId' => $getId,
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
