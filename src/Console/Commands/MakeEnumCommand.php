<?php

namespace OneShot\Builder\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeEnumCommand extends Command
{
    /**
     * The name and signature of the Console command.
     *
     * @var string
     */
    protected $signature = 'make:enum {name}';

    /**
     * The Console command description.
     *
     * @var string
     */
    protected $description = 'Create Enum objects';

    /**
     * Execute the Console command.
     */
    public function handle()
    {
        $enumName   = $this->argument('name');
        $enumPath   = app_path().'\\Enums';
        $namespace  = 'App\\Enums';
        $enumStub   = File::get(base_path('stubs/create.enum.stub'));

        if (str_contains($enumName, '/')) {
            $array      = explode('/', $enumName);
            $enumName   = end($array);

            if (!preg_match('/Enum$/', $enumName)){
                $enumName = end($array).'Enum';
            }

            array_pop($array);
            $enumPath = app_path().'\\Enums\\'.implode('/',$array);
            $namespace   = 'App\\Enums\\'. implode('\\', $array);


        }

        if (!File::exists($enumPath)) {
            File::makeDirectory($enumPath, 0755, true);
        }

        $enumStub = str_replace([ 'DummyClass','DummyNamespace'], [$enumName, $namespace], $enumStub);
        $filePath = $enumPath. '\\' . $enumName . '.php';

        File::put($filePath, $enumStub);

        $this->info("Enum ${enumName} created successfully.");
    }
}