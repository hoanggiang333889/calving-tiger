<?php

namespace LaravelApiCrudGenerator\Generators;

use LaravelApiCrudGenerator\Entities\Table;
use LaravelApiCrudGenerator\Utils\Str;
use LaravelApiCrudGenerator\Utils\TwigExtension;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class Generator
{
    const TYPE_BASE_MODEL = 'baseModel';
    const TYPE_BASE_REPOSITORY = 'baseRepository';
    const TYPE_BASE_SERVICE = 'baseService';
    const TYPE_BASE_CONTROLLER = 'baseController';
    const TYPE_FORM_REQUEST = 'formRequest';
    const TYPE_PAGINATE_REQUEST = 'paginateRequest';
    const TYPE_MODEL = 'model';
    const TYPE_REPOSITORY = 'repository';
    const TYPE_CONTROLLER = 'controller';
    const TYPE_SERVICE = 'service';
    const TYPE_RESOURCE = 'resource';
    const TYPE_CREATE_REQUEST = 'createRequest';
    const TYPE_UPDATE_REQUEST = 'updateRequest';
    const TYPE_ROUTES = 'routes';

    protected string $type;
    protected string $path;
    protected string $fileName;
    protected Environment $twig;
    protected String $table;
    protected String $model;
    protected $fields = [];
    protected String $tableName;


    public function __construct(string $type, string $fileName, String $table = '', String $model = '')
    {
        $this->table = $table;
        $this->model = $model;
        $this->type = $type;
        $this->fileName = $fileName;
        $this->path = self::getPath($type, $this->table ?? null);

        $loader = new FilesystemLoader(__DIR__ . '/../Templates');
        $this->twig = new Environment($loader);
        $this->twig->addExtension(new TwigExtension());
    }

    public static function generate($table = null): bool
    {
        $class = static::class;
        $instance = $table ? new $class($table) : new $class();

        return $instance->handle();
    }

    public static function getPath(string $type, ?string $tableName = null): string
    {
        $tableName = Str::Studly($tableName);
        $pathPattern = config("crudGenerator.path.$type");

        return str_replace('{entity}', $tableName, $pathPattern);
    }

    protected function saveFile(array $data): bool
    {

        $path = __DIR__ . "/../../../../../" . $this->path;
        $fullFileName = $path . '/' . $this->fileName;

        if ($this->type == self::TYPE_ROUTES) {
            $content = file_get_contents($fullFileName);
            $fileContent = $this->twig->render($this->type . '.twig', $data);
            $fileContent = $content . "\r\n" . $fileContent;
            $fileWrited = file_put_contents($fullFileName, $fileContent);

            return $fileWrited !== false;
        }

        if (file_exists($fullFileName)) {
            return false;
        }

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $fileContent = $this->twig->render($this->type . '.twig', $data);

        $fileWrited = file_put_contents($fullFileName, $fileContent);

        return $fileWrited !== false;
    }

    public abstract function handle(): bool;
}
