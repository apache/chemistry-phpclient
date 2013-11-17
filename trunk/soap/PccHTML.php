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

/**
 * PccHTML - HTML definitions for PHP CMIS Client
 *
 * @author Karsten Eberding
 */

class PccHTML {

// Simple template mechanism for HTML code generation, {} will be replaced:
// {$x} will be replaced by parameter x (1 - 9)
// {$template} will be replaced by content of template "template"
// {content} will be replaced by the eval result, e.g. {urlencode('{@cmis:objectId}')}
// recursive brackets are allowed

    private static $templates = array(
        // HTML Code sniplets
        'folderUrl' => '<a href="index.php?folderid={$1}">{$2}</a>',
        'folderListHead' => '<tr><th>Folder Name</th><th></th>',
        'folderListEntry' => "<tr><td>{\$folderUrl}</td><td></td></tr>\n",
        'docListHead' => '<tr><th>Document Name</th><th>Last Modification Date</th><th>Actions</th>',
        'docListEntry' => '<tr><td><a href="index.php?action=download&objectid={$1}">{$2}</a></td><td>{$3}</td><td align="right">{$4}</td></tr>',
        'docListEmpty' => "<tr><td>No documents in this folder</td></tr>\n",
        'pageLink' => '&nbsp;<a href="index.php?folderid={$1}&page={$2}">{$2}</a>&nbsp;',
        'pageCurrent' => '&nbsp;<b>{$1}</b>&nbsp;',
        'pageList' => '<div class="paging">{$1}</div>',
        'downloadFrame' => '<iframe width="1" height="1" frameborder="0" src="index.php?action=download&objectid={$1}"></iframe>',
        'uploadAction' => '<a href="#" onclick="showModal(\'uploadFile\', \'{$1}\', \'{$2}\')">Upload</a>&nbsp;',
        'cancelCOAction' => '<a href="#" onclick="showModal(\'cancelCO\', \'{$1}\', \'{$2}\')">CancelCO</a>&nbsp;',
        'deleteAction' => '<a href="#" onclick="showModal(\'delete\', \'{$1}\', \'{$2}\')">Delete</a>&nbsp;',
        'checkinAction' => '<a href="#" onclick="showModal(\'checkin\', \'{$1}\', \'{$2}\')">Check-In</a>&nbsp;',
        'checkoutAction' => '<a href="#" onclick="showModal(\'checkout\', \'{$1}\', \'{$2}\')">Check-Out</a>&nbsp;',
        'detailsAction' => '<a href="index.php?action=details&objectid={$1}">More</a>&nbsp;',
        'createFolderBtn' => '<input type="button" value="Create Folder" onclick="showModal(\'createFolder\', \'{$1}\')">',
        'createDocumentBtn' => '<input type="button" value="Create Document" onclick="showModal(\'createDocument\', \'{$1}\')">',

        // Dialogs
        'loginForm'        => <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang='en_us'>
    <head>
        <title>Login to PHP CMIS Client</title>
    </head>
    <body>
        <form action="index.php" method="post">
            Username: <input type="text" name="username" /><br />
            Passwort: <input type="password" name="password" /><br />
            <input type="hidden" name="action" value="login">
            <input type="submit" value="Login">
        </form>
    </body>
</html>
EOF
,       'modalDialog'      => <<<EOF
    <div id="{\$1}" class="modalContainer">
        <div class="modalBackground"></div>
        <div class="modalWindow">
            <div class="modalDialog">
                <div class="dialoghead">{\$2}</div>
                <div class="dialogbody">
                {\$3}
                </div>
            </div>
        </div>
    </div>
EOF
,       'uploadFileDialog' => <<<EOF
    Select file to upload for document<div class="dialognotice"></div>
                <form id="{\$1}Form" method="POST" ENCTYPE="multipart/form-data" action="index.php">
                    <table><tbody><tr>
                        <td>File Name:</td><td>
                        <input type="file" name="filename" size="30" value="">
                    </td></tr><tr><td></td><td style="padding-top: 20px;">
                        <input type="hidden" name="action" value="upload">
                        <input type="submit" value="Upload">&nbsp;&nbsp;&nbsp;
                        <input type="reset" value="Cancel" onclick="hideModal('{\$1}');">
                    </td></tr></tbody></table>
                </form>
EOF
,       'cancelCODialog' => <<<EOF
    Confirm Cancel Check-out for document<div class="dialognotice"></div>Unsaved changes will be lost!
                <form id="{\$1}Form" method="GET" action="index.php">
                    <table><tbody><tr><td></td><td style="padding-top: 20px;">
                        <input type="hidden" name="action" value="cancelco">
                        <input type="submit" value="Cancel Check-Out">&nbsp;&nbsp;&nbsp;
                        <input type="reset" value="Cancel" onclick="hideModal('{\$1}');">
                    </td></tr></tbody></table>
                </form>
EOF
,       'deleteDialog' => <<<EOF
    Confirm Deletion of All versions of document<div class="dialognotice"></div>
                <form id="{\$1}Form" method="GET" action="index.php">
                    <table><tbody><tr><td></td><td style="padding-top: 20px;">
                        <input type="hidden" name="action" value="delete">
                        <input type="submit" value="Delete">&nbsp;&nbsp;&nbsp;
                        <input type="reset" value="Cancel" onclick="hideModal('{\$1}');">
                    </td></tr></tbody></table>
                </form>
EOF
,       'checkoutDialog' => <<<EOF
    Check-out document<div class="dialognotice"></div>Download will start automatically.
                <form id="{\$1}Form" method="GET" action="index.php">
                    <table><tbody><tr><td></td><td style="padding-top: 20px;">
                        <input type="hidden" name="action" value="checkout">
                        <input type="submit" value="Check-out and Download">&nbsp;&nbsp;&nbsp;
                        <input type="reset" value="Cancel" onclick="hideModal('{\$1}');">
                    </td></tr></tbody></table>
                </form>
EOF
,       'checkinDialog' => <<<EOF
    Select file to upload for check-in of<div class="dialognotice"></div>(leave file name empty to check-in without uploading a file)
                <form id="{\$1}Form" method="POST" ENCTYPE="multipart/form-data" action="index.php">
                    <table><tbody><tr>
                        <td>File Name:</td><td>
                        <input type="file" name="filename" size="30" value=""></td>
                    </tr><tr><td></td><td style="padding-top: 20px;">
                        <input type="hidden" name="action" value="checkin">
                        <input type="submit" value="Check-In">&nbsp;&nbsp;&nbsp;
                        <input type="reset" value="Cancel" onclick="hideModal('{\$1}');">
                    </td></tr></tbody></table>
                </form>
EOF
,       'createFolderDialog' => <<<EOF
    Create new folder in current folder<div class="dialognotice"></div>
                <form id="{\$1}Form" method="GET" action="index.php">
                    <table><tbody><tr>
                        <td>Folder Name:</td><td>
                        <input type="text" name="createfoldername" size="30" value="">
                        </td></tr><tr><td></td><td style="padding-top: 20px;">
                        <input type="hidden" name="action" value="createfolder">
                        <input type="submit" value="Create">&nbsp;&nbsp;&nbsp;
                        <input type="reset" value="Cancel" onclick="hideModal('{\$1}');">
                    </td></tr></tbody></table>
                </form>
EOF
,       'createDocumentDialog' => <<<EOF
Create new document in current folder<div class="dialognotice"></div>(leave file name empty to create a document without content)
                <form id="{\$1}Form" method="POST" ENCTYPE="multipart/form-data" action="index.php">
                    <table><tbody><tr>
                        <td>File Name:</td><td>
                        <input type="file" name="filename" size="30" value="" 
                            onchange="document.getElementById('createfilename').value=this.value.replace(/^.*(\\\\|\/)/,'')"></td>
                    </tr><tr>
                        <td>Document Name:</td><td>
                        <input type="text" name="createfilename" id="createfilename" size="30" value=""></td>
                    </tr><tr><td></td><td style="padding-top: 20px;">
                        <input type="hidden" name="action" value="createdocument">
                        <input type="submit" value="Create">&nbsp;&nbsp;&nbsp;
                        <input type="reset" value="Cancel" onclick="hideModal('{\$1}');">
                    </td></tr></tbody></table>
                </form>
EOF
,
        // Page elements
        'htmlHeader' => <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang='en_us'>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="CACHE-CONTROL" CONTENT="NO-CACHE">
        <script src="include/dialog.js" type="text/javascript"></script>
        <title>PHP CMIS Client</title>
        <link href="include/pcc.css" rel="stylesheet" type="text/css">
    </head>
    <body>
EOF
,       'htmlFooter' => <<<EOF
    </body>
</html>
EOF
,       'pageHeader' => <<<EOF

        <div class="header">
            <div class="titleContainer">
                <div class="moduleTitle">
                    PHP CMIS Client
                </div>
                <div class="moduleSubTitle">
                    (c) 2012 Karsten Eberding
                </div>
                <div class="logoutTitle">
                    <a href="index.php?action=logout">Logout</a>
                </div>
            </div>
            {\$1}
            <div class="headerText">
                Content in Folder {\$2}
            </div>
            <form id="headButtons">
            {\$3}
            </form>
        </div>
EOF
,       'folderList' => <<<EOF
        <div class="folderList">
            <table>
                <thead>
                {\$folderListHead}
                </thead>
                <tbody>
                {\$1}
                </tbody>
            </table>
        </div>
EOF
,       'documentList' => <<<EOF
        <div class="documentList">
            <table>
                <thead>
                    {\$1}
                </thead>
                <tbody>
                    {\$2}
                </tbody>
            </table>
                {\$3}
        </div>
EOF
    );

    function PccLayout() {
        // nothing to do
    }

    /* Apply Template function, variable parameter list */

    public function applyTemplate($templateName) {
        $param = func_get_args();
        $template = self::$templates[$templateName];
        return $this->applyTemplateString($template, $param);
    }

    public function applyTemplateString($template, $param) {
        // small trick to make class variables available in callback function
        $templates = self::$templates;
        $self = $this;
        $callback = // Start of anonymous callback function
                function ($match) use ($param, $templates, $self) {
                    if ($match[1] == '$') {
                        if (is_numeric($match[2])) {    // Numeric, use parameter $x
                            $return = (string) $param[$match[2]];
                        } else {                        // non-numeric, apply template again
                            $return = $templates[$match[2]];
                            if (strpos($return, '{') !== false) {
                                $return = $self->applyTemplateString($return, $param);
                            }
                        }
                    } else { // does not start with $, eval result
                        $return = $self->applyTemplateString($match[2]);
                        $return = eval("return $return;");
                    }
                    return $return;
                };              // End of anonymous callback function

        $return = preg_replace_callback('/\{([$@]?)(((?>[^\{\}]+)|(?R))*)\}/', $callback, $template);
        return $return;
    }
}

?>
