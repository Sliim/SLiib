#!/bin/bash
if test $# -ne 1
then
  echo "You must specify a class name!"
  exit 1
fi

current_dir=`pwd`
cd ../library
phpunit --skeleton $1

#TODO Move generate skeleton!