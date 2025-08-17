<?php

namespace NexusModsTranslationCollector\Actions\Result;

use Symfony\Component\Filesystem\Filesystem;

class CreateResultFileAction
{
    public function handle(): void
    {
        $path = (new GetPathToResultFileAction())->handle();

        $filesystem = new Filesystem();

        if (! $filesystem->exists($path))  {
            $filesystem->touch($path);

            $file = fopen($path, 'w');
            $data = [
                "Mod ID",
                "Mod Name",
                "URL to Mod",
                "Language",
                "URL to Translation"
            ];

            fputcsv($file, $data, ',', '"', '');

            fclose($file);
        }
    }
}