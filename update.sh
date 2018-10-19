#!/bin/bash

## miningcontrolpanel.com dashboard - update script (git pull)

cd /home2/mcp/public_html/dashboard && git --git-dir=/home2/mcp/public_html/dashboard/.git pull origin master

crontab /home2/mcp/public_html/dashboard/crontab.txt

## chmod 777 global_vars.php