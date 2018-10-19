#!/bin/bash

while /bin/true; do
    killall -9 SCREEN
    /etc/init.d/cgminer stop
    killall -9 cgminer
    /etc/init.d/bmminer stop
    killall -9 bmminer
    sleep 5
done &
