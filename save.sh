#!/bin/sh
./c.sh 
./rmswp.sh
echo "DB root pass:"
./dumpdb.sh
git add .
