<?php

namespace NexusModsTranslationCollector\Actions\NexusMods;

class GetModUrlAction
{
    public function handle(string|int $modId): string
    {
        $gameDomain = env('GAME_DOMAIN');

        return "https://www.nexusmods.com/$gameDomain/mods/{$modId}";
    }
}