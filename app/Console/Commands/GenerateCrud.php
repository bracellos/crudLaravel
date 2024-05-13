<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class GenerateCrud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create crud custom';
    public $name;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->name = $this->argument('name');

        // $this->generateviews();
        $this->generateController();
        // $this->generateModel();
        // $this->generateMigration();
    }

    protected function generateviews(){

        $this->info("INFO - CREATING CUSTOM CRUD VIEWS");

        $index = resource_path("views/{$this->name}/index.blade.php");
        $create = resource_path("views/{$this->name}/create.blade.php");
        $edit = resource_path("views/{$this->name}/edit.blade.php");

        $diretorio = dirname($index);

        if(!File::isDirectory($diretorio)){
            File::makeDirectory($diretorio, 0755, true);
        }

        if(File::exists($index)){
            $this->error("is crud views already exists!");
        }

        $indexTemplate = File::get("./stubs/viewIndex.stub");
        $createTemplate = File::get("./stubs/viewCreate.stub");
        $editTemplate = File::get("./stubs/viewEdit.stub");

        $indexTemplate = str_replace('{texto}','template index', $indexTemplate);
        $createTemplate = str_replace('{texto}','template create', $createTemplate);
        $editTemplate = str_replace('{texto}','template edit', $editTemplate);

        File::put($index, $indexTemplate);
        File::put($create, $createTemplate);
        File::put($edit, $editTemplate);

        $this->info("INFO VIEW {$index} CREATED SUCCESSFULLY");
        $this->info("INFO VIEW {$create} CREATED SUCCESSFULLY");
        $this->info("INFO VIEW {$edit} CREATED SUCCESSFULLY");

    }

    protected function generateController(){

        $this->info("INFO CREATING CUSTOM CONTROLLER");

        $fileName = ucfirst($this->name)."Controller";

        $controller = app_path("Http/Controllers/{$fileName}.php");

        if(File::exists($controller)){
            $this->info("INFO controller {$fileName} is existis");
        }

        $template = File::get("./stubs/crudController.stub");
        $template = str_replace(['{name}'], [$fileName], $template);

        File::put($controller, $template);

        $this->info("INFO controller {$controller} created successfully");
    }

    protected function generateModel(){
        $file = ucfirst($this->name);
        Artisan::call("make:model {$file}");
        $this->info(Artisan::output());
    }

    protected function generateMigration(){
        Artisan::call("make:migration create_{$this->name}_table");
        $this->info(Artisan::output());
    }
}
