<?php
# Licensed to the Apache Software Foundation (ASF) under one
# or more contributor license agreements.  See the NOTICE file
# distributed with this work for additional information
# regarding copyright ownership.  The ASF licenses this file
# to you under the Apache License, Version 2.0 (the
# "License"); you may not use this file except in compliance
# with the License.  You may obtain a copy of the License at
# 
# http://www.apache.org/licenses/LICENSE-2.0
# 
# Unless required by applicable law or agreed to in writing,
# software distributed under the License is distributed on an
# "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
# KIND, either express or implied.  See the License for the
# specific language governing permissions and limitations
# under the License.

/* pcc - PHP CMIS Client
 * 
 * @author Karsten Eberding
 * 
 * URL parameters:
 *      action      upload, download, checkin, checkout, cancelco, 
 *                  default to show list view
 *      objectid    object to perform action on, no objectid shows list view
 *      folderid    current Folder ID, defaults to root folder
 *      page        page number for document list view, defaults to 1
 */

// TODO: logout button
// TODO: delete object and folder
// TODO: detail page for object and folder
// TODO: improved error handling

/* 
 * Part 1: Initialize variables and sessions
 */
require_once 'PccHTML.php';
require_once 'include/CMISWebService.php';
require_once 'include/CMISAlfrescoSoapClient.php';

// Globals
global $errorMessage;       // holds error message from error handler

// General configuration settings
//$soapClient = 'CMISSoapClient';                   // class name for CMIS Soap Client
$soapClient = 'CMISAlfrescoSoapClient';           // class name for CMIS Soap Client
$url = 'http://demoalf:8080/alfresco/cmisws/';  // URL for repository
//$url = 'http://cmis.alfresco.com/cmisws/';    // URL for repository
$cmisServices = array(  // service endpoints for CMIS services, appended to $url
    'RepositoryService' => 'RepositoryService?wsdl',
    'DiscoveryService' => 'DiscoveryService?wsdl',
    'MultiFilingService' => 'MultiFilingService?wsdl',
    'NavigationService' => 'NavigationService?wsdl',
    'ObjectService' => 'ObjectService?wsdl',
    'PolicyService' => 'PolicyService?wsdl',
    'RelationshipService' => 'RelationshipService?wsdl',
    'VersioningService' => 'VersioningService?wsdl',
    'ACLService' => 'ACLService?wsdl',
);
$maxItems = 15;     // max number of documents on a page
$useQuery = true;  // get document list with query, or with getChildren

// Initialize variables and session
$html = new PccHTML();
$errorMesssage = '';
session_start();

// Get session parameters for current folder and page, if set
$currentFolderId = (isset($_SESSION['cmisSession']['currentFolder'])) ? $_SESSION['cmisSession']['currentFolder'] : '';
$currentPage     = (isset($_SESSION['cmisSession']['currentPage']))   ? (int)$_SESSION['cmisSession']['currentPage'] : 1;

// Get URL parameters, handle login
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['folderid']) && ($_GET['folderid'] != '')) {
        $newFolderId = urldecode($_GET['folderid']);
        if ($newFolderId != $currentFolderId) { // reset page if folder changed
            $currentPage = 1;
        }
        $currentFolderId = $newFolderId;
    }
    if (isset($_GET['page']) && ($_GET['page'] != '')) {
        $currentPage = (int)$_GET['page'];
    }
    $currentDocId       = (isset($_GET['objectid'])) ? urldecode($_GET['objectid']) : '';
    $action             = (isset($_GET['action'])) ? $_GET['action'] : '';
    if ($action == 'login') {  // display login form
        echo $html->applyTemplate('loginForm');
        exit;
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['folderid']) && ($_POST['folderid'] != '')) {
        $newFolderId = urldecode($_POST['folderid']);
        if ($newFolderId != $currentFolderId) { // reset page if folder changed
            $currentPage = 1;
        }
        $currentFolderId = $newFolderId;
    }
    if (isset($_POST['page']) && ($_POST['page'] != '')) {
        $currentPage = (int)$_POST['page'];
    }
    $currentDocId       = (isset($_POST['objectid'])) ? urldecode($_POST['objectid']) : '';
    $action             = (isset($_POST['action'])) ? $_POST['action'] : '';
    if ($action == 'login') {  // process login information
        $_SESSION['cmisSession']['username'] = $_POST['username'];
        $_SESSION['cmisSession']['password'] = $_POST['password'];
    }
}

// Forward to login page if credentials are not set
if (!isset($_SESSION['cmisSession']['username']) || !isset($_SESSION['cmisSession']['password'])) {
    $path = 'index.php?action=login';
    header("Location: $path");
    exit;
}

// Open a SOAP session to the CMIS repository
$cmisSessionOptions = array(
    'CMISSoapClient' => $soapClient,        // class name for custom SoapClient
    'CMISServices' => $cmisServices,        // service endpoints
    'user_name' => $_SESSION['cmisSession']['username'],
    'password' => $_SESSION['cmisSession']['password']
    );
try {
    $ecmClient = new CMISWebService($url, $cmisSessionOptions, 'errorMessage');
} catch (Exception $e) {
    error_message($e);
}

// credentials can be set after SOAP initialization, if not provided through options array
// $ecmClient->setCredentials($ecmSession['username'], $ecmSession['password']);

// Get basic information about repository
$repositories = $ecmClient->getRepositories();
$repositoryName = $repositories[0]->repositoryName;     // Using first repository only
$repositoryId = $repositories[0]->repositoryId;
$repositoryInfo = $ecmClient->getRepositoryInfo($repositoryId);
$rootFolderId = $repositoryInfo->rootFolderId;
if ($currentFolderId == '') {
    $currentFolderId = $rootFolderId;
}
$currentFolderName = (string) $currentFolderObject->properties['cmis:name'];
$downloadFrame = '';

// Save current folder and page in session for subsequent page requests
$_SESSION['cmisSession']['currentFolder'] = $currentFolderId;
$_SESSION['cmisSession']['currentPage'] = $currentPage;

/* 
 * Part 2: handle different actions
 */
$pccLeft = '';
$pccRight = '';
switch ($action) {
    case 'logout':
        $ecmClient->clearCredentials();
        unset($_SESSION['cmisSession']['username']);
        unset($_SESSION['cmisSession']['password']);
        $path = 'index.php?action=login';
        header("Location: $path");
        return;
        break;
    case 'createdocument':
        createDocumentAction($ecmClient, $repositoryId, $_FILES['filename'], $_POST['createfilename'], $currentFolderId);
        break;
    case 'createfolder':
        createFolderAction($ecmClient, $repositoryId, $_GET['createfoldername'], $currentFolderId);
        break;
    case 'checkin':
        checkinAction($ecmClient, $repositoryId, $_FILES['filename'], $currentDocId);
        break;
    case 'upload':
        uploadAction($ecmClient, $repositoryId, $_FILES['filename'], $currentDocId);
        break;
    case 'download':
        downloadAction($ecmClient, $repositoryId, $currentDocId);
        return;    // do not continue to display new page
        break;
    case 'checkout':
        $pwcId = urlencode(checkoutAction($ecmClient, $repositoryId, $currentDocId));
        $downloadFrame = $html->applyTemplate('downloadFrame', $pwcId); // start download after page refresh
        break;
    case 'cancelco':
        cancelcoAction($ecmClient, $repositoryId, $currentDocId);
        break;
    case 'delete':
        deleteAction($ecmClient, $repositoryId, $currentDocId);
        break;
    case 'details':
        $pccRight = documentDetails();
        break;
    default:
        break;
}

// Generate output, if not done under action yet
if ($pccLeft == '') {
    $pccLeft = folderList($html, $ecmClient, $repositoryId, $currentFolderId, $rootFolderId, $useQuery);
}
if ($pccRight == '') {
    $pccRight = documentList($html, $ecmClient, $repositoryId, $currentFolderId, $currentPage, $maxItems, $useQuery);
}

/* 
 * Part 3: Display page with folder list and document list or details
 */

/* Build page to display */
echo $html->applyTemplate('htmlHeader');
// all dialogs are included into the main page, to avoid page reloads for dialogs
createModalDialog($html, 'checkin', 'Checkin');
createModalDialog($html, 'checkout', 'Checkout');
createModalDialog($html, 'cancelCO', 'Cancel Checkout');
createModalDialog($html, 'uploadFile', 'Upload File');
createModalDialog($html, 'delete', 'Confirm Delete');
createModalDialog($html, 'createFolder', 'Create Folder');
createModalDialog($html, 'createDocument', 'Create Document');
echo $downloadFrame;    // frame used for file download

// Display header with path and buttons
$pccPath = pccPath($html, $ecmClient, $repositoryId, $currentFolderId, $rootFolderId);
$pccButtons = $html->applyTemplate('createFolderBtn', $currentFolderName)
            . $html->applyTemplate('createDocumentBtn', $currentFolderName);
echo $html->applyTemplate('pageHeader', $errorMessage, $pccPath, $pccButtons);

// Display main page left and right
echo $pccLeft;
echo $pccRight;

return;

/* 
 * End of main program
 */

function pccPath($html, $ecmClient, $repositoryId, $currentFolderId, $rootFolderId) {

    // Get current folder object and name
    $currentFolderObject = $ecmClient->getObject($repositoryId, $currentFolderId, 'cmis:name');

    // Build navigation path
    $path = '';
    $folderId = $currentFolderId;
    while (true) { // loop upwards until root is reached
        $folderObject = $ecmClient->getObject($repositoryId, $folderId, 'cmis:objectId, cmis:name, cmis:parentId');
        $folderUrl = urlencode($folderObject->properties['cmis:objectId']);
        $path = $html->applyTemplate('folderUrl', $folderUrl, $folderObject->properties['cmis:name']) . $path;
        if ($folderId == $rootFolderId) {
            break;
        }
        $path = ' &gt; ' . $path;
        $folderId = (string) $folderObject->properties['cmis:parentId'];
        if (! $folderId) break;    // avoid endless loop in case of error
    }
    return $path;
}

function folderList($html, $ecmClient, $repositoryId, $currentFolderId, $rootFolderId, $useQuery) {
    // Get parent of current folder, set 'UP' for folder list
    $folderList = "\n";
    if ($currentFolderId !== $rootFolderId) {
        $parentFolder = $ecmClient->getFolderParent($repositoryId, $currentFolderId);
        $folderList .= $html->applyTemplate('folderListEntry', urlencode($parentFolder->properties['cmis:objectId']), '../' . $parentFolder->properties['cmis:name']);
    }

    // This code implements two versions to select all folder in the current folder
    // 1) Using a query call. This may not work well with asynchronous search engines like Alfresco solr
    // 2) Using getFolderTree.
    if ($useQuery) {
        $folderObjects = $ecmClient->query($repositoryId, "select * from cmis:folder where cmis:parentId = '$currentFolderId' order by cmis:name");
        // Display all folder objects in folder list
        if (count($folderObjects->objects) > 0) {
            foreach ($folderObjects->objects as $object) {
                // display each folder entry
                $folderList .= $html->applyTemplate('folderListEntry', urlencode($object->properties['cmis:objectId']), $object->properties['cmis:name']);
            }
        }
    } else {
        $folders = $ecmClient->getFolderTree($repositoryId, $currentFolderId, 1, '*');
        $numFolders = count($folders);
        if ($numFolders > 0) {
            $myFolders = array();
            foreach ($folders as $folderContainer) {
                // put into array for sorting
                $object = $folderContainer->objectInFolder->object;
                $myFolders[(string)$object->properties['cmis:name']] = $object;
            }
            ksort($myFolders);  // sort array, getFolderTree does not support sorting
            foreach ($myFolders as $name => $object) {
                // display each folder entry
                $folderList .= $html->applyTemplate('folderListEntry', urlencode($object->properties['cmis:objectId']), $name);
            }
        }
    }
    return $html->applyTemplate('folderList', $folderList);
}

function documentList($html, $ecmClient, $repositoryId, $currentFolderId, $currentPage, $maxItems, $useQuery) {
    // This code implements two versions to select all documents in the current folder
    // 1) Using a query call. This may not work well with asynchronous search engines like Alfresco solr
    // 2) Using getChildren.
    if ($useQuery) {
        $documentObjects = $ecmClient->query($repositoryId, 
                "select * from cmis:document where in_folder('$currentFolderId') order by cmis:name", 
                FALSE, TRUE, enumIncludeRelationships::none, 'cmis:none', $maxItems, ($currentPage - 1) * $maxItems);
    } else {
        $children = $ecmClient->getChildren($repositoryId, $currentFolderId, '*', 'cmis:baseTypeId, cmis:name', 
                true, enumIncludeRelationships::none);
        // create $documentObjects result as it would be returned by query
        $documentObjects = new cmisObjectListType();        // Transform getChildren response into the query return type
        $documentObjects->hasMoreItems = FALSE;
        foreach ($children->objects as $child) {
            if ((string) $child->object->properties['cmis:baseTypeId'] == 'cmis:document') {
                $documentObjects->objects[] = $child->object;
            }
        }
    }
    // Display document list or empty message
    if (count($documentObjects->objects) == 0) {
        $documentHead = $html->applyTemplate('docListEmpty');
    } 
    else {
        $documentHead = $html->applyTemplate('docListHead');
        $documentList = "\n";
        // Loop over all documents
        foreach ($documentObjects->objects as $object) {
            // build list of actions with links for each object
            $actionList = '';   // actions with links
            $objectId = $object->properties['cmis:objectId']->single();
            $objectUrl = urlencode($objectId);
            $objectName = $object->properties['cmis:name']->single();
            if ($object->properties['cmis:isVersionSeriesCheckedOut']->single()) { // object is checked out
                if ($object->allowableActions->canCheckIn) { // allowed to upload and check in
                    $actionList .= $html->applyTemplate('uploadAction', $objectName, $objectUrl);
                    $actionList .= $html->applyTemplate('checkinAction', $objectName, $objectUrl);
                }
                if ($object->allowableActions->canCancelCheckOut) {
                    $actionList .= $html->applyTemplate('cancelCOAction', $objectName, $objectUrl);
                }
            }
            else {    // object is not checked out
                if ($object->allowableActions->canCheckOut) {
                    $actionList .= $html->applyTemplate('checkoutAction', $objectName, $objectUrl);
                }
            }
            if ($object->allowableActions->canDeleteObject) {
                $actionList .= $html->applyTemplate('deleteAction', $objectName, $objectUrl);
            }
            $actionList .= $html->applyTemplate('detailsAction', $objectUrl);
            // Display row with name, date and action links for object
            $documentList .= $html->applyTemplate('docListEntry', $objectUrl, $objectName, 
                    dateString($object->properties['cmis:lastModificationDate']), $actionList) . "\n";
        }
    }
    // Display paging links if more than one page
    $paging = '';
    if ($documentObjects->hasMoreItems || $currentPage > 1) { // display page information
        // if numItems ist set, maxPage can be calculated, otherwise use current or next page
        $maxPage = (isset($documentObjects->numItems)) ? ceil($documentObjects->numItems / $maxItems) :
                ($documentObjects->hasMoreItems ? $currentPage + 1 : $currentPage);
        // display page numbers
        for ($i = 1; $i <= $maxPage; $i++) {
            if (($currentPage) == $i) {
                $paging .= $html->applyTemplate('pageCurrent', $i);
            } else {
                $paging .= $html->applyTemplate('pageLink', urlencode($currentFolderId), $i);
            }
        }
        $paging = $html->applyTemplate('pageList', $paging);
    }
    return $html->applyTemplate('documentList', $documentHead, $documentList, $paging);
}

function documentDetails() {
    // TODO: display detail page for individual object
    $documentList = "<div class=\"documentDetail\">Document Details not yet implemented.</div>\n";
    return $documentList;
}

function dateString($d) {
    $d = date_create($d);
    $d = date_format($d, "d. M Y H:i");
    return $d;
}

// Various actions
function checkoutAction($ecmClient, $repositoryId, $objectId) {
    $response = $ecmClient->checkOut($repositoryId, $objectId);
    return $response->objectId;
}

function cancelcoAction($ecmClient, $repositoryId, $objectId) {
    $object = $ecmClient->getObject($repositoryId, $objectId, '*');
    if (isset($object->properties['cmis:versionSeriesCheckedOutId'])) {
        $response = $ecmClient->cancelCheckOut($repositoryId, $object->properties['cmis:versionSeriesCheckedOutId']);
    }
}

function deleteAction($ecmClient, $repositoryId, $objectId) {
    $ecmClient->deleteObject($repositoryId, $objectId, TRUE);
}

function downloadAction($ecmClient, $repositoryId, $objectId) {

    $object = $ecmClient->getObject($repositoryId, $objectId, '*');
    $streamLength = $object->properties['cmis:contentStreamLength']->single();
    $bufferLength = 0; // define buffer size, or 0 to disable buffering

    if ($bufferLength == 0) { // don't use buffering, not implemented in OpenCMIS < 0.8 !
        $stream = $ecmClient->getContentStream($repositoryId, $objectId, '');
        header('Content-Type: ' . $stream->mimeType);
        header('Content-Disposition: attachment; filename=' . $stream->filename);
        header('Content-Length; ' . $streamLength);
        echo $stream->stream;
        flush();
    } else {
        // Send first content chunk, up to bufferLength
        $stream = $ecmClient->getContentStream($repositoryId, $objectId, '', 0, $bufferLength);
        header('Content-Type: ' . $stream->mimeType);
        header('Content-Disposition: attachment; filename=' . $stream->filename);
        header('Content-Length; ' . $streamLength);
        echo $stream->stream;
        flush();

        // Send additional chunks if required
        $offset = $bufferLength;
        while ($offset < $streamLength) {
            $stream = $ecmClient->getContentStream($repositoryId, $objectId, NULL, $offset, $bufferLength);
            echo $stream->stream;
            flush();
            $offset += $bufferLength;
        }
    }
}

function checkinAction($ecmClient, $repositoryId, $file, $objectId) {
    $stream = NULL;
    $major = false;
    $comment = NULL;
    if (is_array($file) && $file['error'] == 0) {
        $stream = new cmisContentStreamType();
        $stream->length = $file['size'];
        $stream->mimeType = $file['type'];
        $stream->filename = $file['name'];
        $stream->stream = file_get_contents($file['tmp_name']);
    }
    $object = $ecmClient->getObject($repositoryId, $objectId, '*');
    if (isset($object->properties['cmis:versionSeriesCheckedOutId'])) {
        $response = $ecmClient->checkIn($repositoryId, $object->properties['cmis:versionSeriesCheckedOutId'], $major, NULL, $stream, $comment);
    }
}

function uploadAction($ecmClient, $repositoryId, $file, $objectId) {
    $stream = new cmisContentStreamType();
    $stream->length = $file['size'];
    $stream->mimeType = $file['type'];
    $stream->filename = $file['name'];
    $stream->stream = file_get_contents($file['tmp_name']);

    $ecmClient->setContentStream($repositoryId, $objectId, NULL, NULL, $stream);
}

function createFolderAction($ecmClient, $repositoryId, $createName, $folderId) {
    $properties['cmis:name'] = new cmisPropertyString($createName);
    $properties['cmis:objectTypeId'] = new cmisPropertyId('cmis:folder');
    $response = $ecmClient->createFolder($repositoryId, $properties, $folderId);
}

function createDocumentAction($ecmClient, $repositoryId, $file, $createName, $folderId) {
    $properties['cmis:name'] = new cmisPropertyString($createName);
    $properties['cmis:objectTypeId'] = new cmisPropertyId('cmis:document');
    $stream = new cmisContentStreamType();
    if (is_array($file) && $file['error'] == 0) {
        $stream->length = $file['size'];
        $stream->mimeType = $file['type'];
        $stream->filename = $file['name'];
        $stream->stream = file_get_contents($file['tmp_name']);
    }
    $response = $ecmClient->createDocument($repositoryId, $properties, $folderId, $stream);
}

// Modal dialog
function createModalDialog($html, $name, $title) {
    echo $html->applyTemplate('modalDialog', $name, $title,
         $html->applyTemplate($name . 'Dialog', $name));
}

function errorMessage($sf) {
    global $errorMessage;
    // Display Soap Fault error message on page, if any
    $errorMessage .= "<div class=\"errorMessage\"><div class=\"ErrorTitle\">SOAP Error:</div>\n" . $sf->getMessage() . "</div>\n";
}
?>