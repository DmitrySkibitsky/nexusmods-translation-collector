<?php

namespace NexusModsTranslationCollector\Actions\Result;

use NexusModsTranslationCollector\Enum\LogFileEnum;

class GetPathToResultFileAction
{
    public function handle(): string
    {
        return 'results/'.LogFileEnum::mods->value;
    }
}