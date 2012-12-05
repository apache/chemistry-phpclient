README for PHP CMIS Client


The PHP CMIS Soap Client consists of the following files:

1) CMIS Web Service Library

CMISWebService.php - the main class to be included into an application
cmisTypeDefinitions.php - the CMIS object model, used by CMISWebService.php
CMISSoapClient.php - extends the PHP SoapClient class, used by CMISWebService.php
CMISAlfrescoSoapClient.php - an Alfresco specific version, includes ticket support

2) PHP CMIS Client

index.php - the main file, includes all CMIS calls and program logic. Configuration
    parameters, including URL for service endpoints, is configured here.
PccHTML.php - includes all HTML presentation code, no calls to CMIS
pcc.css - CSS file, included from PccHTML.php
dialog.js - Java Script for dialog handling, included from PccHTML.php

The PHP CMIS Client is a sample application to show the use of the CMIS library.
Main features include folder and document list, checkin, checkout, upload,
download, cancel checkout and delete.

All CMIS functions and the program logic are included into the main file index.php.
Changes to the HTML presentation can be done independently in PccHTML.php.
Jacascipt is used for dialog handling, to avoid page reloads for a dialog. 
A simple templating mechanism is included, to generate the HTML output.

This software is presented to the Apache Chemistry project. Licensing will
be changed to ASF if accepted by the Chemistry project.

Contact information:

Karsten Eberding
karsten@eberding.eu