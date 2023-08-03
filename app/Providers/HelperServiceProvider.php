<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     */
    public function boot(): void
    {
        $rdi = new RecursiveDirectoryIterator(app_path('Helpers'.DIRECTORY_SEPARATOR.'Global'));
        $it = new RecursiveIteratorIterator($rdi);

        /** @var DirectoryIterator $it */
        while ($it->valid()) {
            if (
                ! $it->isDot() &&
                $it->isFile() &&
                $it->isReadable() &&
                $it->current()->getExtension() === 'php' &&
                strpos($it->current()->getFilename(), 'Helper')
            ) {
                require $it->key();
            }

            $it->next();
        }
    }
}
