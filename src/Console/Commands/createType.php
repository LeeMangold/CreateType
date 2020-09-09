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

        $modelStub = $this->createTemplate( $name, 'Model' );
        file_put_contents(app_path("{$name}.php"), $modelStub);
        $this->line("\t$name Model was Created!");

        $contollerStub = $this->createTemplate( $name, 'Controller' );
        file_put_contents(app_path("Http/Controllers/{$name}sController.php"), $contollerStub);
        $this->line("\t$name Controller was Created!");

        $requestStub = $this->createTemplate( $name, 'Request' );
        file_put_contents(app_path("Http/Requests/{$name}Request.php"), $requestStub);
        $this->line("\t$name Request was Created!");

        $migration_name=date("Y_m_d_His") . "_create_{$name}s_table.php";
        $lcName = strtolower($name);
        $migrationStub = $this->createTemplate( $lcName, "migration" );
        file_put_contents(database_path("migrations/{$migration_name}"), $migrationStub);
        $this->line("\t$name Migration was Created!");


        $this->info("\tCreating Views");

        $viewStub = $this->createTemplate( $name, 'Views/create.blade' );
        $this->force_file_put_contents(resource_path("/views/{$name}s/create.blade.php"), $viewStub);
        $this->line("\t\tCreate View Created!");

        $viewStub = $this->createTemplate( $name, 'Views/edit.blade' );
        $this->force_file_put_contents(resource_path("/views/{$name}s/edit.blade.php"), $viewStub);
        $this->line("\t\tEdit View Created!");

        $viewStub = $this->createTemplate( $name, 'Views/form.blade' );
        $this->force_file_put_contents(resource_path("/views/{$name}s/form.blade.php"), $viewStub);
        $this->line("\t\tForm View Created!");

        $viewStub = $this->createTemplate( $name, 'Views/index.blade' );
        $this->force_file_put_contents(resource_path("/views/{$name}s/index.blade.php"), $viewStub);
        $this->line("\t\tIndex View Created!");

        $viewStub = $this->createTemplate( $name, 'Views/show.blade' );
        $this->force_file_put_contents(resource_path("/views/{$name}s/show.blade.php"), $viewStub);
        $this->line("\t\tShow View Created!");


        $this->line("\tCreating Routes!");
        $routesStub = $this->createTemplate( $name, 'routes' );
        file_put_contents(base_path("routes/web.php"), $routesStub, FILE_APPEND);


        $this->info("$name Created! Don't forget your migration");



        return 0;
    }

    /**
     * @param string|name
     * @return string|string[]
     */
    public function createTemplate( $name, $template )
    {

        $packagePath = base_path("vendor/leemangold/createtype/");
        $stubPath = $packagePath . "/src/Console/Stubs/";

        $stub = file_get_contents( $stubPath . "{$template}.stub"  );
        $stub = str_replace( "MODELNAME", $name, $stub );
        return $stub;
    }

    function force_file_put_contents (string $fullPathWithFileName, string $fileContents)
    {
        $exploded = explode(DIRECTORY_SEPARATOR,$fullPathWithFileName);
        array_pop($exploded);
        $directoryPathOnly = implode(DIRECTORY_SEPARATOR,$exploded);
        if (!File::exists($directoryPathOnly))
        {
            File::makeDirectory($directoryPathOnly,0775,true,false);
        }
        File::put($fullPathWithFileName,$fileContents);
    }




}


/*
echo "" >> ../routes/web.php
echo "Route::get('/$1', '$1sController@index')->name('$1s');" >> ../routes/web.php
echo "Route::get('/$1/new', '$1sController@create')->name('$1s.create');" >> ../routes/web.php
echo "Route::post('/$1/new', '$1sController@store')->name('$1s.store');" >> ../routes/web.php
echo "Route::get('/$1/{id}', '$1sController@show')->name('$1s.show');" >> ../routes/web.php
echo "Route::get('/$1/{id}/edit', '$1sController@edit')->name('$1s.edit');" >> ../routes/web.php
echo "Route::patch('/$1/{id}/update', '$1sController@update')->name('$1s.update');" >> ../routes/web.php
echo "Route::delete('/$1/{id}', '$1sController@destroy')->name('$1s.destroy');" >> ../routes/web.php






mkdir ../resources/views/$1s
sed "s/MODELNAME/$1/g" view/create.blade.php > ../resources/views/$1s/create.blade.php
sed "s/MODELNAME/$1/g" view/edit.blade.php > ../resources/views/$1s/edit.blade.php
sed "s/MODELNAME/$1/g" view/form.blade.php > ../resources/views/$1s/form.blade.php
sed "s/MODELNAME/$1/g" view/index.blade.php > ../resources/views/$1s/index.blade.php
sed "s/MODELNAME/$1/g" view/show.blade.php > ../resources/views/$1s/show.blade.php

sed "s/MODELNAME/$1/g" Model.php > ../app/$1.php
sed "s/MODELNAME/$1/g" Controller.php > ../app/Http/Controllers/$1sController.php
sed "s/MODELNAME/$1/g" Request.php > ../app/Http/Requests/$1Request.php
migration=`date +%Y_%m_%d_%H%M%S_create_$1s_table.php`
echo $migration;

sed "s/MODELNAME/$lc/g" migration.php > ../database/migrations/$migration





*/
