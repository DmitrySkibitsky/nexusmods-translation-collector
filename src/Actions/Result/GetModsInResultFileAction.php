<?php

namespace NexusModsTranslationCollector\Actions\Result;

class GetModsInResultFileAction
{
    public function handle(): array
    {
        (new CreateResultFileAction())->handle();

        $path = (new GetPathToResultFileAction())->handle();

        $rows = [];
        if (($handle = fopen($path, 'r')) !== false) {
            while (($data = fgetcsv($handle)) !== false) {
                $rows[] = $data;
            }
            fclose($handle);
        }

        unset($rows[0]);

        return $rows;
    }
}