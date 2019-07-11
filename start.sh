#!/bin/bash

if [ -e .env ]; then
    source .env
else
    echo "Please set up your .env file before starting your environment."
    exit 1
fi


docker-compose build

docker-compose -f docker-compose.yml up -d

sleep 10;

#docker exec beer composer update

docker exec beer php commande/createsql.php

echo
echo "#-----------------------------------------------------------"
echo "#"
echo "# Please check your browser to see if it is running "
echo "#"
echo "#-----------------------------------------------------------"
echo

exit 0
