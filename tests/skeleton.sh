#!/bin/bash
if test $# -ne 1
then
    echo >&2 "You must specify a class name!"
    exit 1
fi

ext=".php"
pwd=`pwd`
deep=`echo $1 | sed "s/\// /g" | wc -w`

if test $deep -lt 2
then
    echo >&2 "Class name $1 invalid. Exiting.."
    exit 2
fi

dir=`echo $1 | cut -f -$(($deep-1)) -d /`
class=$(echo $1 | sed -e "s/\//\\\/")
file=`echo $1 | cut -f $deep -d /`"Test"$ext
destination_directory=$pwd/library/$dir

if test -f $destination_directory/$file
then
    echo "Test $file already exists, exiting.."
    exit 3
fi

cd ../library/
phpunit --bootstrap $pwd/Bootstrap.php --skeleton-test $class

if test $? -ne 0
then
    echo >&2 "PHPUnit returned an error when generating the skeleton"
    cd $pwd
    echo "Exiting.."
    exit 4
fi

if test ! -d $destination_directory
then
    mkdir -p $destination_directory
fi

mv $dir/$file $destination_directory/$file

cd $pwd
echo "done."
exit 0