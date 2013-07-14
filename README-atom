           Apache Chemistry (Incubating) PHP CMIS Client Library
           -----------------------------------------------------

ABOUT
 Thanks for using phpclient, the CMIS client library for PHP.

 The goal of this library is to provide an interoperable API to CMIS
 repositories such as Alfresco, Nuxeo, KnowledgeTree, MS SharePoint,
 EMC Documentum, and any other content repository that is CMIS-compliant.

 More info on CMIS can be found at:
 http://www.oasis-open.org/committees/cmis

SOURCE
 The source code for this project lives at http://incubator.apache.org/chemistry/


The atom library has been refactored and is under atom

TRYING THE LIBRARY
 To Run this example execute the following

 php -f cmis_ls.php <rest-endpoint> <username> <password> <folderpath> [debug option 1|2]

 Notes:
  + The if the folder path has spaces in it you must URL encode the folder path i.e. /Data Dictionary --> /Data+Dictionary
  + The debug option can be omitted. If it is 1 the program will display all of the arrays associated with the objects that
    are returned.  If the debug option is 2 or more, then the XML returned will also be displayed
  + This will not work on Pre CMIS-1.0 repositories
  + There is virtually no error checking.
  + Your version of php must support DOMDocument and curl

EXAMPLE RUNS
 php -f cmis_ls.php   http://cmis.alfresco.com/service/api/cmis  admin admin /
 php -f cmis_ls.php   http://cmis.alfresco.com/service/api/cmis  admin admin /Data+Dictionary 1

FUNCTIONALITY
 The cmis_repository_wrapper.php library provided the following functionality
 + Encapsulates access to a CMIS 1.0 compliant repository
 + Provides utilities for getting information out of:
   + Workspace (the repositoryInfo)
   + Object Entries
   + Non-Hierarchical Object feeds

 More information will be on the Wiki for this Google Code Project and on
 the author's blog http://oldschooltechie.com/

DOCS
The docs were built with the following command
phpdoc -t docs/atom -d  atom

Testing
php ../utils/phpunit.phar <classname>
php -e -d display_errors=On ../utils/phpunit.phar --debug -v <classname>
==============
Adding Calls that mimick the domain model

==============
Potential enhancements
+ performance enhancements -- storing session/repository information
+ web services binding
+ PHP Provider framework
