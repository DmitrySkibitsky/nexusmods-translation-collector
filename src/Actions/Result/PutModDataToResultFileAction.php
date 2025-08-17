<?php

namespace NexusModsTranslationCollector\Actions\Result;

use NexusModsTranslationCollector\Actions\NexusMods\GetModUrlAction;
use NexusModsTranslationCollector\DTO\PutModDataToResultFileDTO;

class PutModDataToResultFileAction
{
    private static string|null $path = null;

    public function handle(
        PutModDataToResultFileDTO $data
    ): void {
        if (self::$path === null) {
            self::$path = (new GetPathToResultFileAction())->handle();
        }

        $file = fopen(self::$path, 'a');

        $data = [
            $data->modDTO->id,
            $data->modDTO->name,
            (new GetModUrlAction())->handle($data->modDTO->id),
            $data->language,
            $data->url,
        ];

        fputcsv($file, $data, ',', '"', '');

        fclose($file);
    }
}