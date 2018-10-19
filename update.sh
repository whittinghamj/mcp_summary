#!/bin/bash

## miningcontrolpanel.com summary - update script (git pull)

cd /home2/mcp/public_html/summary && git --git-dir=/home2/mcp/public_html/summary/.git pull origin master

## chmod 777 global_vars.php