## Description
Script that collects a list of mods that have been translated 
based on the nexusmods collection.

## Required
- php >= 8.3
- composer
- firefox + geckodriver

## Install
```bash
cp .env.example .env
```
- change the env file

## Use
```bash
php index.php
```
### Result
`results/mods.csv` - contains the entire list of mods + links to mods that have translations