<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;

class MakeCrud extends Command
{
    protected $signature = 'make:crud {model}';

    protected $description = 'Create CRUD Controller + Service + Requests with consistent naming and ApiResponse trait';

    public function handle()
    {
        $model = Str::studly($this->argument('model'));
        $controller = "{$model}Controller";
        $service = "{$model}Service";
        $storeRequest = "Store{$model}Request";
        $updateRequest = "Update{$model}Request";

        if (!is_dir(app_path('Services'))) {
            mkdir(app_path('Services'), 0755, true);
        }
        if (!is_dir(app_path('Http/Controllers'))) {
            mkdir(app_path('Http/Controllers'), 0755, true);
        }
        if (!is_dir(app_path('Http/Requests'))) {
            mkdir(app_path('Http/Requests'), 0755, true);
        }

        Artisan::call('make:request', ['name' => $storeRequest]);
        Artisan::call('make:request', ['name' => $updateRequest]);
        $this->info("Created requests: {$storeRequest}, {$updateRequest}");

        $servicePath = app_path("Services/{$service}.php");
        if (!file_exists($servicePath)) {
            file_put_contents($servicePath, $this->generateService($model));
            $this->info("Service {$service} created.");
        } else {
            $this->warn("Service {$service} already exists.");
        }

        $controllerPath = app_path("Http/Controllers/{$controller}.php");
        if (!file_exists($controllerPath)) {
            file_put_contents($controllerPath, $this->generateController($model));
            $this->info("Controller {$controller} created.");
        } else {
            $this->warn("Controller {$controller} already exists.");
        }
    }

    protected function generateService($model)
    {
        $namespace = "App\\Services";
        $modelFull = "App\\Models\\{$model}";
        $modelPluralStudly = Str::plural($model);
        $modelVar = Str::camel($model);

        return <<<PHP
<?php

namespace {$namespace};

use {$modelFull};
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class {$model}Service
{
    public function list{$modelPluralStudly}(): Collection
    {
        return {$model}::all();
    }

    public function find{$model}(int \$id): {$model}
    {
        return {$model}::findOrFail(\$id);
    }

    public function create{$model}(array \$data): {$model}
    {
        return {$model}::create(\$data);
    }

    public function update{$model}(int \$id, array \$data): {$model}
    {
        \$item = \$this->find{$model}(\$id);
        \$item->update(\$data);
        return \$item;
    }

    public function delete{$model}(int \$id): bool
    {
        \$item = \$this->find{$model}(\$id);
        return \$item->delete();
    }
}
PHP;
    }

    protected function generateController($model)
    {
        $controller = "{$model}Controller";
        $service = "{$model}Service";
        $modelVar = Str::camel($model);
        $modelPluralStudly = Str::plural($model);
        $storeRequest = "Store{$model}Request";
        $updateRequest = "Update{$model}Request";

        return <<<PHP
<?php

namespace App\Http\Controllers;

use App\Http\Requests\\{$storeRequest};
use App\Http\Requests\\{$updateRequest};
use App\Services\\{$service};
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class {$controller} extends Controller
{
    use ApiResponse;

    protected \${$modelVar}Service;

    public function __construct({$service} \${$modelVar}Service)
    {
        \$this->{$modelVar}Service = \${$modelVar}Service;
    }

    public function list{$modelPluralStudly}(): JsonResponse
    {
        \$data = \$this->{$modelVar}Service->list{$modelPluralStudly}();
        return \$this->successResponse(\$data, '{$modelPluralStudly} retrieved successfully.');
    }

    public function create{$model}({$storeRequest} \$request): JsonResponse
    {
        \$item = \$this->{$modelVar}Service->create{$model}(\$request->validated());
        return \$this->successResponse(\$item, '{$model} created successfully.', Response::HTTP_CREATED);
    }

    public function find{$model}(int \$id): JsonResponse
    {
        \$item = \$this->{$modelVar}Service->find{$model}(\$id);
        return \$this->successResponse(\$item, '{$model} retrieved successfully.');
    }

    public function update{$model}({$updateRequest} \$request, int \$id): JsonResponse
    {
        \$item = \$this->{$modelVar}Service->update{$model}(\$id, \$request->validated());
        return \$this->successResponse(\$item, '{$model} updated successfully.');
    }

    public function delete{$model}(int \$id): JsonResponse
    {
        \$this->{$modelVar}Service->delete{$model}(\$id);
        return \$this->successResponse(null, '{$model} deleted successfully.', Response::HTTP_NO_CONTENT);
    }
}
PHP;
    }
}