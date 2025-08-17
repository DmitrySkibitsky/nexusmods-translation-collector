## Description
Script that collects mods from a nexusmods collection that have a translation.

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
If the script hangs (can be identified by the process bar), restart the script. 
The mod processing will start from the last mod where the script stopped.

### Result
`results/mods.csv` - contains the entire list of mods + links to mods that have translations