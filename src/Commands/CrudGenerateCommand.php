<?php

namespace LaravelApiCrudGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use LaravelApiCrudGenerator\Generators\BaseControllerGenerator;
use LaravelApiCrudGenerator\Generators\BaseModelGenerator;
use LaravelApiCrudGenerator\Generators\BaseServiceGenerator;
use LaravelApiCrudGenerator\Generators\BaseRepositoryGenerator;
use LaravelApiCrudGenerator\Generators\FormRequestGenerator;
use LaravelApiCrudGenerator\Generators\ModelGenerator;
use LaravelApiCrudGenerator\Generators\RepositoryGenerator;
use LaravelApiCrudGenerator\Generators\RoutesGenerator;
use LaravelApiCrudGenerator\Generators\ControllerGenerator;
use LaravelApiCrudGenerator\Generators\ServiceGenerator;
use LaravelApiCrudGenerator\Generators\ResourceGenerator;
use LaravelApiCrudGenerator\Generators\CreateRequestGenerator;
use LaravelApiCrudGenerator\Generators\UpdateRequestGenerator;
use LaravelApiCrudGenerator\Repositories\TableRepository;

class CrudGenerateCommand extends Command
{
    protected $signature = 'api:crud {--model=} {--module=}';
    protected $description = 'Generate API CRUD.';
    protected $tableRepository;

    public function __construct(TableRepository $tableRepository)
    {
        parent::__construct();

        $this->tableRepository = $tableRepository;
    }

    public function handle()
    {
        $table = Str::plural($this->option('model'));
        $table = Str::snake($table);

        FormRequestGenerator::generate();
        BaseRepositoryGenerator::generate();
        BaseControllerGenerator::generate();
        BaseModelGenerator::generate();
        BaseServiceGenerator::generate();

        $messageAddRoutes = "Add the following routes: \n\n";

        if (Schema::hasTable($table)) {
            $this->info('Table "' . $table . '" already existed');
            $column = Schema::getColumnListing($table);
            if (!count($column)) {
                $this->error('No fields found');

                return;
            }
        }

        ModelGenerator::generate($table);
        RepositoryGenerator::generate($table);
        ControllerGenerator::generate($table);
        ServiceGenerator::generate($table);
        ResourceGenerator::generate($table);
        CreateRequestGenerator::generate($table);
        UpdateRequestGenerator::generate($table);
        RoutesGenerator::generate($table);

        $pathRoutes = RoutesGenerator::getPath(RoutesGenerator::TYPE_ROUTES, $table);
        $messageAddRoutes .= "Route::group([], base_path('$pathRoutes/routes.php'));\n";

        $this->info("CRUD created successfully.\n\n Remove the namespace route.\n" . $messageAddRoutes);
    }
}
