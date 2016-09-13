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
            str_replace( '-listview', '', snake_case($this->argument('name'), '-') )
        );

        $from = __DIR__ . '/stubs/list.blade.stub';
        $targetPath = resource_path("views/{$name}/");
        $fileName = 'list.blade.php';

        if( $this->files->exists($targetPath . $fileName) )
        {
            $this->error("File views/{$name}/{$fileName} already exists!");
            return;
        }

        if( !$this->files->isDirectory($targetPath) )
        {
            $this->files->makeDirectory($targetPath);
        }

        if( $this->files->copy($from, $targetPath . $fileName) )
        {
            $this->info("Created views/{$name}/{$fileName}");
            return;
        }

        $this->error("Could not create views/{$name}/{$fileName}");
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