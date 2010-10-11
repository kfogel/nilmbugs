#!/bin/sh

svn up -q

VERSION="0.0.`svnversion | sed -e 's/[A-Z]//g'`"
DISTNAME=nilmbugs-${VERSION}

rm -rf ${DISTNAME} ${DISTNAME}.tar.gz
mkdir ${DISTNAME}
cd ${DISTNAME}
cp ../README .
sed -e "s/-VERSION/-${VERSION}/g" < README > README.tmp
mv README.tmp README
cp -a ../nilmbugs .
pwd
find nilmbugs -type d -name ".svn" | xargs rm -rf
rm nilmbugs/.htaccess
echo -n "<? ?>" > nilmbugs/peoplepods/lib/etc/options.php
cd ..
tar zcvf ${DISTNAME}.tar.gz ${DISTNAME}
rm -rf ${DISTNAME}
echo ""
ls -l ${DISTNAME}.tar.gz
echo ""
