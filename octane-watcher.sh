#!/bin/bash

ARTISAN_PATH="/var/www/nepstrading/artisan"

# Check status using artisan
STATUS=$(/usr/bin/php $ARTISAN_PATH octane:status 2>&1)

if [[ $STATUS != *"is running"* ]]; then
    echo "$(date): Octane is down. Restarting..." >> /var/www/nepstrading/storage/logs/octane-watcher.log
    cd /var/www/nepstrading
    # Kill any zombie roadrunner processes just in case
    pkill -f "roadrunner" || true
    /usr/bin/nohup /usr/bin/php $ARTISAN_PATH octane:start --server=roadrunner --host=127.0.0.1 --port=8000 > /dev/null 2>&1 &
fi
