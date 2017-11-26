<?php

namespace Administr\ListView\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeListView extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'administr:listview';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a ListView class.';

    protected $type = 'ListView class';

    public function fire()
    {
        parent::fire();

        $name = str_plural(
            str_replace( '-list-view', '', snake_case($this->argument('name'), '-') )
        );

        $from = __DIR__ . '/stubs/list.blade.stub';

        $viewPath = config('administr.viewPath');

        if(strlen($viewPath) > 0) {
            $viewPath .= '/';
        }

        $targetPath = resource_path("views/{$viewPath}{$name}/");
        $fileName = 'list.blade.php';

        if( $this->files->exists($targetPath . $fileName) )
        {
            $this->error("File views/{$viewPath}{$name}/{$fileName} already exists!");
            return;
        }

        if( !$this->files->isDirectory($targetPath) )
        {
            $this->files->makeDirectory($targetPath);
        }

        if( $this->files->copy($from, $targetPath . $fileName) )
        {
            $this->info("Created views/{$viewPath}{$name}/{$fileName}");
            return;
        }

        $this->error("Could not create views/{$viewPath}{$name}/{$fileName}");
    }


    protected function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);

        $prefix = config('administr.prefix');

        $noListViewName = str_replace('ListView', '', $this->getNameInput());
        $dummyRoute =  strlen($prefix) > 0 ? $prefix . '.' : '';
        $dummyRoute .= str_plural(
            strtolower( snake_case( $noListViewName, '-' ) )
        );
        $stub = str_replace('dummyroute', $dummyRoute, $stub);

        return $stub;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/ListView.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\ListViews';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the ListView class.'],
        ];
    }
}