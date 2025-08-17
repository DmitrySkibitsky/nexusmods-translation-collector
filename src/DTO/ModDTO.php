<?php

namespace NexusModsTranslationCollector\DTO;

class ModDTO
{
    public function __construct(
        public string $name,
        public int $id
    ) {
    }
}