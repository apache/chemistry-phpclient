rm ../dist/cmis-phplib.tgz ../dist/cmis-phplib.zip
tar -zcvf ../dist/cmis-phplib.tgz *.php cmis/*.php
zip -v ../dist/cmis-phplib.zip *.php cmis/*.php
