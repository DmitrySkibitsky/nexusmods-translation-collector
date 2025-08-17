<?php

namespace NexusModsTranslationCollector\Services;

use GuzzleHttp\Exception\GuzzleException;
use NexusModsTranslationCollector\Actions\NexusMods\CollectModsWithTranslationAction;
use NexusModsTranslationCollector\Actions\NexusMods\GetCollectionWithModsAction;
use NexusModsTranslationCollector\DTO\CollectionModsDTO;

class NexusModsService
{
    /**
     * @throws GuzzleException
     */
    private function getCollectionWithMods(): CollectionModsDTO
    {
        return (new GetCollectionWithModsAction())
            ->handle();
    }

    /**
     * @throws GuzzleException
     */
    public function collectModsWithTranslation(): void
    {
        $collectionWithMods = $this->getCollectionWithMods();

        (new CollectModsWithTranslationAction())
            ->handle(
                collectionModsDTO: $collectionWithMods
            );
    }
}