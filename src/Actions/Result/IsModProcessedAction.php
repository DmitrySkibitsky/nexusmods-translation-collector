<?php

namespace NexusModsTranslationCollector\Actions\Result;

use NexusModsTranslationCollector\DTO\ModDTO;

class IsModProcessedAction
{
    public function handle(
        ModDTO $modDTO
    ): bool {
        $mods = (new GetModsInResultFileAction())->handle();

        return count(
            array_filter(
                $mods,
                function (array $item) use ($modDTO) {
                    return ($item[0] ?? null) == $modDTO->id;
                }
            )
        ) > 0;
    }
}