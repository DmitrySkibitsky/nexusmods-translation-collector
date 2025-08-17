<?php

namespace NexusModsTranslationCollector\DTO;

class PutModDataToResultFileDTO
{
    public function __construct(
        public ModDTO $modDTO,
        public string $url,
        public string $language,
    ) {
    }
}