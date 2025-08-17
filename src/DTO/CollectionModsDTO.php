<?php

namespace NexusModsTranslationCollector\DTO;

class CollectionModsDTO
{
    /**
     * @param int $count
     * @param ModDTO[] $mods
     */
    public function __construct(
        public int $count,
        public array $mods
    ) {
    }
}