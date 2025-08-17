<?php

namespace NexusModsTranslationCollector\Actions\NexusMods;

use Exception as Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use NexusModsTranslationCollector\Actions\Result\IsModProcessedAction;
use NexusModsTranslationCollector\DTO\CollectionModsDTO;
use NexusModsTranslationCollector\DTO\ModDTO;

class GetCollectionWithModsAction
{
    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function handle(): CollectionModsDTO
    {
        $client = new Client([
            'base_uri' => 'https://api.nexusmods.com/',
            'headers' => [
                'apikey' => env('API_KEY'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]
        ]);

        $response = $client->post('v2/graphql', [
            'json' => [
                'query' => $this->getQuery(),
                'variables' => [
                    'slug' => env('COLLECTION_SLUG'),
                    'domainName' => env('GAME_DOMAIN')
                ]
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        if (!isset($data['data']['collection'])) {
            throw new Exception('Not found Collection');
        }

        $collection = $data['data']['collection'];

        $modFiles = $collection['currentRevision']['modFiles'] ?? [];

        $mods = [];

        foreach ($modFiles as $modFile) {
            $mod = $modFile['file']['mod'];
            $modId = explode(',', $mod['id'])[1];

            $modDTO = new ModDTO(
                name: $mod['name'],
                id: $modId,
            );

            if ((new IsModProcessedAction())->handle($modDTO)) {
                continue;
            }

            $mods[] = $modDTO;
        }

        $count = count($mods);

        return new CollectionModsDTO(
            count: $count,
            mods: $mods
        );
    }

    private function getQuery(): string
    {
        return <<<'GRAPHQL'
            query GetCollection($slug: String!, $domainName: String) {
              collection(slug: $slug, domainName: $domainName) {
                name
                slug
                game {
                  domainName
                }
                currentRevision {
                  modFiles {
                    id
                    fileId
                    gameId
                    file {
                      mod {
                        id
                        name
                        game {
                          domainName
                        }
                      }
                    }
                  }
                }
              }
            }
        GRAPHQL;
    }
}