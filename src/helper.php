<?php

function env(string $key, $default = null): mixed
{
    return $_ENV[$key] ?? $default;
}