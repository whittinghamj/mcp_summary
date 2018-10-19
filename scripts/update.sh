#!/bin/bash

## ZEUS Controller - Update Script (git pull)

cd /zeus/controller && git --git-dir=/zeus/controller/.git pull origin master

crontab /zeus/controller/crontab.txt

chmod 777 global_vars.php