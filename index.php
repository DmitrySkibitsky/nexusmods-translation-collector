<?php

require 'vendor/autoload.php';

set_time_limit(0);

use NexusModsTranslationCollector\Services\NexusModsService as NexusModsService;
use Symfony\Component\Dotenv\Dotenv as Dotenv;

$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__.'/.env');

$nexusModsService = new NexusModsService();
$nexusModsService->collectModsWithTranslation();
