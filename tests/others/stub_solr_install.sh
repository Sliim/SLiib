#!/bin/bash
#
# This script download and install an instance of SolR 3.5.0
# This instance is used as stubs for unit tests
#
# Note that you need a JVM to launch solr
#
pwd=`pwd`

readonly SOLR_URL="http://apache.multidist.com/lucene/solr/3.5.0/apache-solr-3.5.0.tgz"
readonly TMP_DIR="/tmp"
readonly INSTALL_DIR="${pwd}/Stubs/SolR"
readonly PATCHES_DIR="${pwd}/../files/patches/solr_stub"

if test -d ${INSTALL_DIR}; then
    echo "SolR stub already installed, abording install.."
    exit 0
fi

cd ${TMP_DIR}
wget ${SOLR_URL}

if test ${?} -ne 0; then
    echo >&2 "Downloading failed, existing.."
    exit 1
fi

set -e

echo "Extract solr instance.."
tar xf apache-solr-3.5.0.tgz

echo "Remove useless files.."
cd apache-solr-3.5.0/example
rm -r example-DIH exampledocs README.txt work multicore

patch solr/conf/solrconfig.xml ${PATCHES_DIR}/solrconfig.xml.patch
patch solr/conf/schema.xml ${PATCHES_DIR}/schema.xml.patch

echo "Move solr directory in INSTALL_DIR.."
cd ..
mv example/ ${INSTALL_DIR}

cd ${pwd}

echo "Remove tmp files.."
rm -r ${TMP_DIR}/apache-solr*

set +e

echo "done."
exit 0
