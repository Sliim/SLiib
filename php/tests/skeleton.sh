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

if test $deep -lt 2
then
    echo "Class name $1 invalid. Exiting.."
    exit 2
fi

dir=`echo $1 | cut -f -$(($deep-1)) -d _ | sed 's/_/\//g'`
file=`echo $1 | cut -f $deep -d _`"Test"$ext
destination_directory=$pwd/library/$dir

cd ../library/
phpunit --bootstrap $pwd/Bootstrap.php --skeleton-test $1

if test $? -ne 0
then
    echo 'PHPUnit returned an error when generating the skeleton'
    cd $pwd
    echo 'Exiting..'
    exit 3
fi

if test ! -d $destination_directory
then
    mkdir -p $destination_directory
fi
mv $dir/$generate_file $destination_directory/$file

cd $pwd
echo "done."
exit 0