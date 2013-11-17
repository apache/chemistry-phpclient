rm ../../CMIS-LIB-DIST/cmis-phplib.tgz ../../CMIS-LIB-DIST/cmis-phplib.zip
rm ../../CMIS-LIB-DIST/cmis-phplib.tgz.asc ../../CMIS-LIB-DIST/cmis-phplib.zip.asc
tar -zcvf ../../CMIS-LIB-DIST/cmis-phplib.tgz *.php cmis/*.php
zip -v ../../CMIS-LIB-DIST/cmis-phplib.zip *.php cmis/*.php
cd ../../CMIS-LIB-DIST
gpg --armor --output cmis-phplib.tgz.asc --detach-sig cmis-phplib.tgz
gpg --armor --output cmis-phplib.zip.asc --detach-sig cmis-phplib.zip

