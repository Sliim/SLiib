#!/bin/bash
if test $# -ne 1
then
  echo "You must specify a class name!"
  exit 1
fi

ext=".php"
pwd=`pwd`
generate_file=$1"Test"$ext
deep=`echo $1 | sed 's/_/ /g' | wc -w`
dir=`echo $1 | cut -f -$(($deep-1)) -d _ | sed 's/_/\//g'`
file=`echo $1 | cut -f $deep -d _`"Test"$ext

cd ../library/
phpunit --skeleton-test $1

if test $? -ne 0
then
  echo 'PHPUnit returned an error when generating the skeleton'
  echo 'Exiting..'
  exit 2
fi

if test ! -d $pwd/$dir
then
  mkdir -p $pwd/$dir
fi
mv $dir/$generate_file $pwd/$dir/$file

cd $pwd
echo "done."
exit 0