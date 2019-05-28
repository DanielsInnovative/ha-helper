#!/bin/bash

connectorDir="/var/lib/ha-helper/connectors/"
apacheDir="/var/www/html/"

mkdir -p $connectorDir

cp connectors/* $connectorDir
cp ha-helper.php $apacheDir

echo "Connectors installed to $connectorDir"
echo "ha-helper.php application installed to $apacheDir"
