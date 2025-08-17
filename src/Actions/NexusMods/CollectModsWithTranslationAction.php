<?php

namespace NexusModsTranslationCollector\Actions\NexusMods;

use NexusModsTranslationCollector\Actions\Result\PutModDataToResultFileAction;
use NexusModsTranslationCollector\DTO\CollectionModsDTO;
use NexusModsTranslationCollector\DTO\PutModDataToResultFileDTO;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Panther\Client;

class CollectModsWithTranslationAction
{
    public function handle(
        CollectionModsDTO $collectionModsDTO
    ): void {
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $progressBar = new ProgressBar($output, $collectionModsDTO->count);

        $progressBar->start();

        $langs = explode(',', env('MODS_TRANSLATION', []));
        $langs = array_map('trim', $langs);
        $langs = array_filter($langs);

        foreach ($collectionModsDTO->mods as $index => $mod) {
            $client = Client::createFirefoxClient();

            $modUrl = (new GetModUrlAction())
                ->handle(
                    modId: $mod->id,
                );

            echo "\nMod ID: ".$mod->id;
            echo "\nMod Name: ".$mod->name;
            echo "\nMod URL: ".$modUrl;

            $progressBar->advance();

            $response = $client
                ->request('GET', $modUrl);

            $existsTranslation = false;

            foreach ($langs as $lang) {
                $url = null;

                try {
                    $url = $response
                        ->filter(".translation-table a.flag.flag-$lang")
                        ->attr('href');
                } catch (\Exception $e) {
                    $message = $e->getMessage();
                    if (! str_contains($message, 'The current node list is empty')) {
                        echo "\n Error: ".$e->getMessage();
                    }
                }

                if ($url !== null) {
                    $existsTranslation = true;

                    (new PutModDataToResultFileAction())
                        ->handle(
                            data: new PutModDataToResultFileDTO(
                                modDTO: $mod,
                                url: $url,
                                language: $lang
                            )
                        );
                }
            }

            if (!$existsTranslation) {
                (new PutModDataToResultFileAction())
                    ->handle(
                        data: new PutModDataToResultFileDTO(
                            modDTO: $mod,
                            url: '',
                            language: ''
                        )
                    );
            }

            sleep(1);
        }

        $progressBar->finish();
    }
}