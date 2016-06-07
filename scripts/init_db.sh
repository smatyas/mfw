#!/usr/bin/env bash
mysql -h db -u mfw -pmfw mfw < ./scripts/dump.sql
OUT=$?
if [ $OUT -eq 0 ];then
   echo "Database successfully imported."
else
   echo "Database import error."
fi
