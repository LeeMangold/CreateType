<?php

namespace LeeMangold\CreateType\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class createType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lee:make:type {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a custom data type with CRUD';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $name = $this->argument('name');
        $this->info("Generating CRUD for $name");

        $modelStub = $this->createTemplate($name, 'Model');
        file_put_contents(app_path("Models/{$name}.php"), $modelStub);
        $this->line("\t$name Model was Created!");

        $contollerStub = $this->createTemplate($name, 'Controller');
        file_put_contents(app_path("Http/Controllers/{$name}sController.php"), $contollerStub);
        $this->line("\t$name Controller was Created!");

        $requestStub = $this->createTemplate($name, 'Request');
        $this->force_file_put_contents(app_path("Http/Requests/{$name}Request.php"), $requestStub);
        $this->line("\t$name Request was Created!");

        $migration_name = date("Y_m_d_His")."_create_{$name}s_table.php";
        $lcName = strtolower($name);
        $migrationStub = $this->createTemplate($lcName, "migration");
        file_put_contents(database_path("migrations/{$migration_name}"), $migrationStub);
        $this->line("\t$name Migration was Created!");


        $this->info("\tCreating Views");

        $viewStub = $this->createTemplate($name, 'Views/create.blade');
        $this->force_file_put_contents(resource_path("/views/{$name}s/create.blade.php"), $viewStub);
        $this->line("\t\tCreate View Created!");

        $viewStub = $this->createTemplate($name, 'Views/edit.blade');
        $this->force_file_put_contents(resource_path("/views/{$name}s/edit.blade.php"), $viewStub);
        $this->line("\t\tEdit View Created!");

        $viewStub = $this->createTemplate($name, 'Views/form.blade');
        $this->force_file_put_contents(resource_path("/views/{$name}s/form.blade.php"), $viewStub);
        $this->line("\t\tForm View Created!");

        $viewStub = $this->createTemplate($name, 'Views/index.blade');
        $this->force_file_put_contents(resource_path("/views/{$name}s/index.blade.php"), $viewStub);
        $this->line("\t\tIndex View Created!");

        $viewStub = $this->createTemplate($name, 'Views/show.blade');
        $this->force_file_put_contents(resource_path("/views/{$name}s/show.blade.php"), $viewStub);
        $this->line("\t\tShow View Created!");


        $this->line("\tCreating Routes!");
        $routesStub = $this->createTemplate($name, 'routes');
        file_put_contents(base_path("routes/web.php"), $routesStub, FILE_APPEND);

        $this->info("$name Created! Don't forget your migration");


        return 0;
    }

    /**
     * @param  string|name
     * @return string|string[]
     */
    public function createTemplate($name, $template)
    {

        $packagePath = base_path("vendor/leemangold/createtype/");
        $stubPath = $packagePath."/src/Console/Stubs/";

        $stub = file_get_contents($stubPath."{$template}.stub");
        $stub = str_replace("MODELNAME", $name, $stub);
        return $stub;
    }

    function force_file_put_contents(string $fullPathWithFileName, string $fileContents)
    {
        $exploded = explode(DIRECTORY_SEPARATOR, $fullPathWithFileName);
        array_pop($exploded);
        $directoryPathOnly = implode(DIRECTORY_SEPARATOR, $exploded);
        if ( ! File::exists($directoryPathOnly) ) {
            File::makeDirectory($directoryPathOnly, 0775, true, false);
        }
        File::put($fullPathWithFileName, $fileContents);
    }


}