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
 * CMISAlfrescoSoapClient extends the standard SoapClient class to include message handling for CMIS for Alfresco:
 * 1) include WebService Security (WSS) headers as required by CMIS (this part is taken from Alfresco)
 * 2) handle multipart response messages from the repository
 * 3) handle Alfresco tickets
 * 
 * @author Karsten Eberding, based on sample code from Alfresco
 */
class CMISAlfrescoSoapClient extends SoapClient {

    private static $securityExtNS = "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd";
    private static $wsUtilityNS = "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd";
    private static $passwordType = "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText";

    private static $myUsername;
    private static $myPassword;
    private static $ticket;

    public function __construct($wsdl, $options) {

        $wsdlcache = $this->get_wsdl($wsdl);

        // Call the base class
        parent::__construct($wsdlcache, $options);
    }

    public function setCredentials($url, $username, $password) {

        // Store the current username, password, ticket
        self::$myUsername = $username;
        self::$myPassword = $password;
        
        if (isset($_SESSION['AlfrescoTicket'])) {
            $ticket = $_SESSION['AlfrescoTicket'];
            return true;
        }

        // get url for ticket service
        $u  =  urlencode($username);
        $pw = urlencode($password);
        $url = parse_url($url);
        $path = preg_replace('#/[^/]+/?$#', '/', $url['path']); // remove last path element
        $url = (isset($url['scheme']) ? $url['scheme'] : 'http') . '://' . $url['host'] .
                (isset($url['port']) ? ':'.$url['port'] : '') . "{$path}service/api/login?u=$u&pw=$pw";

        // get ticket
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

        $ticketxml = curl_exec($ch);
        curl_close($ch);
        $dom = new DOMDocument("1.0");
        $dom->loadXML($ticketxml);
        $ticket = $dom->getElementsByTagName('ticket')->item(0)->nodeValue;

        if (isset($ticket)) {
            self::$ticket = $ticket;                // save ticket for subsequent calls
            $_SESSION['AlfrescoTicket'] = $ticket;  // save ticket for subsequent page requests
            return true;    // login success
        }
        return false;   // login failure
    }

    public function clearCredentials() {
        // Clear the current username, password, ticket
        self::$myUsername = '';
        self::$myPassword = '';
        self::$ticket = '';
        $_SESSION['AlfrescoTicket'] = '';
    }

    public function __call($function_name, $arguments = array()) {
        return $this->__soapCall($function_name, $arguments);
    }

    public function __soapCall($function_name, $arguments = array(), $options = array(), $input_headers = array(), $output_headers = array()) {

        // Automatically add a security header         
        $input_headers[] = new SoapHeader(self::$securityExtNS, "Security", null, 1);

        $response = parent::__soapCall($function_name, $arguments, $options, $input_headers, $output_headers);

        return $response;
    }

    public function __doRequest($request, $location, $action, $version) {
        // If this request requires authentication we have to manually construct the
        // security headers.
        $dom = new DOMDocument("1.0");
        $dom->loadXML($request);

        $securityHeader = $dom->getElementsByTagName("Security");

        if ($securityHeader->length != 1) {
            throw new Exception("Expected length: 1, Received: " . $securityHeader->length . ". No Security Header, or more than one element called Security!");
        }

        $securityHeader = $securityHeader->item(0);

        // Construct Timestamp Header
        $timeStamp = $dom->createElementNS(self::$wsUtilityNS, "Timestamp");
        $createdDate = date("Y-m-d\TH:i:s\Z", mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")));
        $expiresDate = date("Y-m-d\TH:i:s\Z", mktime(date("H") + 24, date("i"), date("s"), date("m"), date("d"), date("Y")));
        $created = new DOMElement("Created", $createdDate, self::$wsUtilityNS);
        $expires = new DOMElement("Expires", $expiresDate, self::$wsUtilityNS);
        $timeStamp->appendChild($created);
        $timeStamp->appendChild($expires);

        // Construct UsernameToken Header
        if (isset(self::$ticket) && self::$ticket != '') {
            $username = '';
            $password = self::$ticket;
        } else {
            $username = self::$myUsername;
            $password = self::$myPassword;
        }
        $userNameToken = $dom->createElementNS(self::$securityExtNS, "UsernameToken");
        $userName = new DOMElement("Username", $username, self::$securityExtNS);

        $passWord = $dom->createElementNS(self::$securityExtNS, "Password");
        $typeAttr = new DOMAttr("Type", self::$passwordType);
        $passWord->appendChild($typeAttr);

        $passWord->appendChild($dom->createTextNode($password));
        $userNameToken->appendChild($userName);
        $userNameToken->appendChild($passWord);

        // Construct Security Header
        $securityHeader->appendChild($timeStamp);
        $securityHeader->appendChild($userNameToken);

        // Save the XML Request
        $request = $dom->saveXML();

        $response = parent::__doRequest($request, $location, $action, $version);
        $responseHeader = $this->__getLastResponseHeaders();

        $boundary = array();
        $start = array();
        $contentId = array();
        $attachment = array();
        $match = array();
        if (preg_match('/content-type: multipart\/related;/i', $responseHeader) === 1 &&
                preg_match('/boundary="(.*?)";/i', $responseHeader, $boundary) === 1 &&
                preg_match('/start="<(.*?)>";/i', $responseHeader, $start)) {
            $parts = explode("\r\n--" . $boundary[1], $response);

            foreach ($parts as $part) {
                //if ($part == '' || $part == '--') continue;
                $part = explode("\r\n\r\n", $part, 2); // array of part header and content
                if (count($part) < 2)
                    continue;
                if (preg_match('/content-id:\s*<(.*?)>/i', $part[0], $contentId) === 1) {
                    if ($contentId[1] == $start[1]) {
                        $response = $part[1];
                    } else {
                        $attachment[$contentId[1]] = $part[1];
                        //$attachment[] = $part[1];
                    }
                }
            }
            if (count($attachment) > 0) {
                if (preg_match('/<xop:include .*?href="cid:(.*?)".*?>/i', $response, $match)) {
                    $response = str_replace($match[0], base64_encode($attachment[$match[1]]), $response);
                }
            }
        }

        return $response;
    }

    private function get_wsdl($url) {
        $cache_file = "/tmp/soap.wsdl." . md5($url);

        //only fetch a new wsdl every hour
        if (!file_exists($cache_file) || filectime($cache_file) < time() - 3600) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

            $wsdldata = curl_exec($ch);
            curl_close($ch);

            $wsdldata = str_replace('anySimpleType', 'string', $wsdldata);

            file_put_contents($cache_file, $wsdldata);
            if (!file_exists($cache_file)) {
                throw new Exception("Couldn't load WSDL at {$url}");
            }
        }
        return $cache_file;
    }

}

?>
