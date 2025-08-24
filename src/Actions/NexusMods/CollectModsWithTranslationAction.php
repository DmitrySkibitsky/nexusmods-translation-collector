<?php

namespace NexusModsTranslationCollector\Actions\NexusMods;

use NexusModsTranslationCollector\Actions\Result\PutModDataToResultFileAction;
use NexusModsTranslationCollector\DTO\CollectionModsDTO;
use NexusModsTranslationCollector\DTO\PutModDataToResultFileDTO;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Panther\Client;

class CollectModsWithTranslationAction
{
    private Client $client;

    /**
     * @throws \Exception
     */
    public function handle(
        CollectionModsDTO $collectionModsDTO
    ): void {
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $progressBar = new ProgressBar($output, $collectionModsDTO->count);

        $progressBar->start();

        $langs = explode(',', env('MODS_TRANSLATION', []));
        $langs = array_map('trim', $langs);
        $langs = array_filter($langs);

        $timeBetweenRequests = (int) env('TIMEOUT_BETWEEN_REQUESTS', 1);

        $this->client = Client::createChromeClient();

        try {
            foreach ($collectionModsDTO->mods as $mod) {
                $modUrl = (new GetModUrlAction())
                    ->handle(
                        modId: $mod->id,
                    );

                echo "\nMod ID: ".$mod->id;
                echo "\nMod Name: ".$mod->name;
                echo "\nMod URL: ".$modUrl;

                $progressBar->advance();

                $response = $this
                    ->client
                    ->request('GET', $modUrl);

                $pageContent = $response->html();

                if (str_contains($pageContent, 'DDoS attacks')) {
                    echo "\nWarning about a DDoS attack!";
                    echo "\nIncrease the TIMEOUT_BETWEEN_REQUESTS parameter";

                    break;
                }

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

                sleep($timeBetweenRequests + array_rand([1, 2, 3, 4, 5]));
            }

            $progressBar->finish();
        } catch (\Exception $e) {
            $this->client->quit();

            throw $e;
        }
    }
}