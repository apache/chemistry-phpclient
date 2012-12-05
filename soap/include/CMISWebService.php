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

require_once('CMISSoapClient.php');
require_once('cmisTypeDefinitions.php');

/**
 * CMISWebService class
 * 
 *  
 *
 * This library implements the CMIS 1.0 Web Services binding for PHP,
 * as published by OASIS (http://www.oasis-open.org/committees/cmis/)
 *
 * @author    Karsten Eberding
 * @package   CMISWebService
 * @version   0.6
 */

class CMISWebService {

    private $DiscoveryService;
    private $MultiFilingService;
    private $NavigationService;
    private $ObjectService;
    private $PolicyService;
    private $RelationshipService;
    private $RepositoryService;
    private $VersioningService;
    private $ACLService;

    private $url;
    
    private static $classmap = array(
        'enumDecimalPrecision' => 'enumDecimalPrecision',
        'enumContentStreamAllowed' => 'enumContentStreamAllowed',
        'enumCardinality' => 'enumCardinality',
        'enumUpdatability' => 'enumUpdatability',
        'enumDateTimeResolution' => 'enumDateTimeResolution',
        'enumPropertyType' => 'enumPropertyType',
        'enumBaseObjectTypeIds' => 'enumBaseObjectTypeIds',
        'enumCapabilityQuery' => 'enumCapabilityQuery',
        'enumCapabilityJoin' => 'enumCapabilityJoin',
        'enumCapabilityContentStreamUpdates' => 'enumCapabilityContentStreamUpdates',
        'enumVersioningState' => 'enumVersioningState',
        'enumUnfileObject' => 'enumUnfileObject',
        'enumRelationshipDirection' => 'enumRelationshipDirection',
        'enumIncludeRelationships' => 'enumIncludeRelationships',
        'enumPropertiesBase' => 'enumPropertiesBase',
        'enumPropertiesDocument' => 'enumPropertiesDocument',
        'enumPropertiesFolder' => 'enumPropertiesFolder',
        'enumPropertiesRelationship' => 'enumPropertiesRelationship',
        'enumPropertiesPolicy' => 'enumPropertiesPolicy',
        'cmisObjectType' => 'cmisObjectType',
        'cmisPropertiesType' => 'cmisPropertiesType',
        'cmisProperty' => 'cmisProperty',
        'cmisPropertyBoolean' => 'cmisPropertyBoolean',
        'cmisPropertyId' => 'cmisPropertyId',
        'cmisPropertyInteger' => 'cmisPropertyInteger',
        'cmisPropertyDateTime' => 'cmisPropertyDateTime',
        'cmisPropertyDecimal' => 'cmisPropertyDecimal',
        'cmisPropertyHtml' => 'cmisPropertyHtml',
        'cmisPropertyString' => 'cmisPropertyString',
        'cmisPropertyUri' => 'cmisPropertyUri',
        'cmisChoice' => 'cmisChoice',
        'cmisChoiceBoolean' => 'cmisChoiceBoolean',
        'cmisChoiceId' => 'cmisChoiceId',
        'cmisChoiceInteger' => 'cmisChoiceInteger',
        'cmisChoiceDateTime' => 'cmisChoiceDateTime',
        'cmisChoiceDecimal' => 'cmisChoiceDecimal',
        'cmisChoiceHtml' => 'cmisChoiceHtml',
        'cmisChoiceString' => 'cmisChoiceString',
        'cmisChoiceUri' => 'cmisChoiceUri',
        'cmisAllowableActionsType' => 'cmisAllowableActionsType',
        'cmisListOfIdsType' => 'cmisListOfIdsType',
        'cmisPropertyDefinitionType' => 'cmisPropertyDefinitionType',
        'cmisPropertyBooleanDefinitionType' => 'cmisPropertyBooleanDefinitionType',
        'cmisPropertyIdDefinitionType' => 'cmisPropertyIdDefinitionType',
        'cmisPropertyIntegerDefinitionType' => 'cmisPropertyIntegerDefinitionType',
        'cmisPropertyDateTimeDefinitionType' => 'cmisPropertyDateTimeDefinitionType',
        'cmisPropertyDecimalDefinitionType' => 'cmisPropertyDecimalDefinitionType',
        'cmisPropertyHtmlDefinitionType' => 'cmisPropertyHtmlDefinitionType',
        'cmisPropertyStringDefinitionType' => 'cmisPropertyStringDefinitionType',
        'cmisPropertyUriDefinitionType' => 'cmisPropertyUriDefinitionType',
        'cmisTypeDefinitionType' => 'cmisTypeDefinitionType',
        'cmisTypeDocumentDefinitionType' => 'cmisTypeDocumentDefinitionType',
        'cmisTypeFolderDefinitionType' => 'cmisTypeFolderDefinitionType',
        'cmisTypeRelationshipDefinitionType' => 'cmisTypeRelationshipDefinitionType',
        'cmisTypePolicyDefinitionType' => 'cmisTypePolicyDefinitionType',
        'cmisQueryType' => 'cmisQueryType',
        'cmisRepositoryInfoType' => 'cmisRepositoryInfoType',
        'cmisRepositoryCapabilitiesType' => 'cmisRepositoryCapabilitiesType',
        'enumTypeOfChanges' => 'enumTypeOfChanges',
        'enumCapabilityChanges' => 'enumCapabilityChanges',
        'cmisChangeEventType' => 'cmisChangeEventType',
        'enumACLPropagation' => 'enumACLPropagation',
        'enumCapabilityACL' => 'enumCapabilityACL',
        'enumBasicPermissions' => 'enumBasicPermissions',
        'cmisPermissionDefinition' => 'cmisPermissionDefinition',
        'cmisPermissionMapping' => 'cmisPermissionMapping',
        'enumAllowableActionsKey' => 'enumAllowableActionsKey',
        'enumUsers' => 'enumUsers',
        'cmisAccessControlPrincipalType' => 'cmisAccessControlPrincipalType',
        'cmisAccessControlEntryType' => 'cmisAccessControlEntryType',
        'cmisAccessControlListType' => 'cmisAccessControlListType',
        'cmisACLCapabilityType' => 'cmisACLCapabilityType',
        'enumSupportedPermissions' => 'enumSupportedPermissions',
        'enumCapabilityRendition' => 'enumCapabilityRendition',
        'enumRenditionKind' => 'enumRenditionKind',
        'cmisRenditionType' => 'cmisRenditionType',
        'cmisFaultType' => 'cmisFaultType',
        'enumServiceException' => 'enumServiceException',
        'cmisExtensionType' => 'cmisExtensionType',
        'cmisTypeContainer' => 'cmisTypeContainer',
        'cmisTypeDefinitionListType' => 'cmisTypeDefinitionListType',
        'cmisObjectInFolderContainerType' => 'cmisObjectInFolderContainerType',
        'cmisObjectListType' => 'cmisObjectListType',
        'cmisObjectInFolderType' => 'cmisObjectInFolderType',
        'cmisObjectParentsType' => 'cmisObjectParentsType',
        'cmisObjectInFolderListType' => 'cmisObjectInFolderListType',
        'cmisRepositoryEntryType' => 'cmisRepositoryEntryType',
        'cmisContentStreamType' => 'cmisContentStreamType',
        'cmisACLType' => 'cmisACLType',
        'getRepositories' => 'getRepositories',
        'getRepositoriesResponse' => 'getRepositoriesResponse',
        'getRepositoryInfo' => 'getRepositoryInfo',
        'getRepositoryInfoResponse' => 'getRepositoryInfoResponse',
        'getTypeChildren' => 'getTypeChildren',
        'getTypeChildrenResponse' => 'getTypeChildrenResponse',
        'getTypeDescendants' => 'getTypeDescendants',
        'getTypeDescendantsResponse' => 'getTypeDescendantsResponse',
        'getTypeDefinition' => 'getTypeDefinition',
        'getTypeDefinitionResponse' => 'getTypeDefinitionResponse',
        'getDescendants' => 'getDescendants',
        'getDescendantsResponse' => 'getDescendantsResponse',
        'getFolderTree' => 'getFolderTree',
        'getFolderTreeResponse' => 'getFolderTreeResponse',
        'getChildren' => 'getChildren',
        'getChildrenResponse' => 'getChildrenResponse',
        'getFolderParent' => 'getFolderParent',
        'getFolderParentResponse' => 'getFolderParentResponse',
        'getObjectParents' => 'getObjectParents',
        'getObjectParentsResponse' => 'getObjectParentsResponse',
        'getRenditions' => 'getRenditions',
        'getRenditionsResponse' => 'getRenditionsResponse',
        'getCheckedOutDocs' => 'getCheckedOutDocs',
        'getCheckedOutDocsResponse' => 'getCheckedOutDocsResponse',
        'createDocument' => 'createDocument',
        'createDocumentResponse' => 'createDocumentResponse',
        'createDocumentFromSource' => 'createDocumentFromSource',
        'createDocumentFromSourceResponse' => 'createDocumentFromSourceResponse',
        'createFolder' => 'createFolder',
        'createFolderResponse' => 'createFolderResponse',
        'createRelationship' => 'createRelationship',
        'createRelationshipResponse' => 'createRelationshipResponse',
        'createPolicy' => 'createPolicy',
        'createPolicyResponse' => 'createPolicyResponse',
        'getAllowableActions' => 'getAllowableActions',
        'getAllowableActionsResponse' => 'getAllowableActionsResponse',
        'getProperties' => 'getProperties',
        'getPropertiesResponse' => 'getPropertiesResponse',
        'getObject' => 'getObject',
        'getObjectResponse' => 'getObjectResponse',
        'getObjectByPath' => 'getObjectByPath',
        'getObjectByPathResponse' => 'getObjectByPathResponse',
        'getContentStream' => 'getContentStream',
        'getContentStreamResponse' => 'getContentStreamResponse',
        'updateProperties' => 'updateProperties',
        'updatePropertiesResponse' => 'updatePropertiesResponse',
        'moveObject' => 'moveObject',
        'moveObjectResponse' => 'moveObjectResponse',
        'deleteObject' => 'deleteObject',
        'deleteObjectResponse' => 'deleteObjectResponse',
        'deleteTree' => 'deleteTree',
        'deleteTreeResponse' => 'deleteTreeResponse',
        'failedToDelete' => 'failedToDelete',
        'setContentStream' => 'setContentStream',
        'setContentStreamResponse' => 'setContentStreamResponse',
        'deleteContentStream' => 'deleteContentStream',
        'deleteContentStreamResponse' => 'deleteContentStreamResponse',
        'addObjectToFolder' => 'addObjectToFolder',
        'addObjectToFolderResponse' => 'addObjectToFolderResponse',
        'removeObjectFromFolder' => 'removeObjectFromFolder',
        'removeObjectFromFolderResponse' => 'removeObjectFromFolderResponse',
        'query' => 'query',
        'queryResponse' => 'queryResponse',
        'getContentChanges' => 'getContentChanges',
        'getContentChangesResponse' => 'getContentChangesResponse',
        'checkOut' => 'checkOut',
        'checkOutResponse' => 'checkOutResponse',
        'cancelCheckOut' => 'cancelCheckOut',
        'cancelCheckOutResponse' => 'cancelCheckOutResponse',
        'checkIn' => 'checkIn',
        'checkInResponse' => 'checkInResponse',
        'getPropertiesOfLatestVersion' => 'getPropertiesOfLatestVersion',
        'getPropertiesOfLatestVersionResponse' => 'getPropertiesOfLatestVersionResponse',
        'getObjectOfLatestVersion' => 'getObjectOfLatestVersion',
        'getObjectOfLatestVersionResponse' => 'getObjectOfLatestVersionResponse',
        'getAllVersions' => 'getAllVersions',
        'getAllVersionsResponse' => 'getAllVersionsResponse',
        'getObjectRelationships' => 'getObjectRelationships',
        'getObjectRelationshipsResponse' => 'getObjectRelationshipsResponse',
        'applyPolicy' => 'applyPolicy',
        'applyPolicyResponse' => 'applyPolicyResponse',
        'removePolicy' => 'removePolicy',
        'removePolicyResponse' => 'removePolicyResponse',
        'getAppliedPolicies' => 'getAppliedPolicies',
        'getAppliedPoliciesResponse' => 'getAppliedPoliciesResponse',
        'getACL' => 'getACL',
        'getACLResponse' => 'getACLResponse',
        'applyACL' => 'applyACL',
        'applyACLResponse' => 'applyACLResponse',
        'anyURI' => 'anyURI',
        );

    /**
     * This class implements the CMIS 1.0 Web Services binding for PHP,
     * as published by OASIS (http://www.oasis-open.org/committees/cmis/)
     *
     * @param string url - URL to CMIS Service Endpoint
     * @param string options - array with SOAP options
     */
    
    public function CMISWebService ($url = "http://democrm/demo/cmiswsdl/test/", $options = array(), $errorFunction = NULL) {

        // set default options if not defined in options parameter
        if (!array_key_exists('trace', $options)) $options['trace'] = true;
        if (!array_key_exists('exceptions', $options)) $options['exceptions'] = true;
        if (!array_key_exists('features', $options)) $options['features'] = SOAP_SINGLE_ELEMENT_ARRAYS + SOAP_USE_XSI_ARRAY_TYPE;
        if (!array_key_exists('soap_version', $options)) $options['soap_version'] = SOAP_1_1;
        //if (!array_key_exists('cache_wsdl', $options)) $options['cache_wsdl'] = WSDL_CACHE_NONE; // for debugging
        
        // set url and error function
        $this->url = $url;
        $this->errorFunction = $errorFunction;

        // initialize classmap (merge)
        foreach(self::$classmap as $key => $value) {
            if(!isset($options['classmap'][$key])) {
                $options['classmap'][$key] = $value;
            }
        }            

        // create individual soap service objects
        $soapClient = (array_key_exists('CMISSoapClient', $options)) ? $options['CMISSoapClient'] : 'CMISSoapClient';
        $cmisServices = (array_key_exists('CMISServices', $options)) ? $options['CMISServices'] : array(
        'DiscoveryService' => 'DiscoveryService?wsdl',
        'MultiFilingService' => 'MultiFilingService?wsdl',
        'NavigationService' => 'NavigationService?wsdl',
        'ObjectService' => 'ObjectService?wsdl',
        'PolicyService' => 'PolicyService?wsdl',
        'RelationshipService' => 'RelationshipService?wsdl',
        'RepositoryService' => 'RepositoryService?wsdl',
        'VersioningService' => 'VersioningService?wsdl',
        'ACLService' => 'ACLService?wsdl',

        );
        
        foreach ($cmisServices as $serviceName => $serviceEndpoint) {
            $this->$serviceName = new $soapClient($url . $serviceEndpoint, $options);
        }

        // set credentials if provided through options
        if (array_key_exists('user_name', $options) && array_key_exists('password', $options)) {
            $this->setCredentials($options['user_name'], $options['password']);
        }
    }
    
    /**
     * 
     * setCredentials - sets username and password if not provided in constructor
     *
     * @param string username
     * @param string password
     */
    public function setCredentials($username, $password) {
        $this->RepositoryService->setCredentials($this->url, $username, $password);
    }
    
    /**
     * 
     * clearCredentials - clears credential information of current session
     *
     */
    public function clearCredentials() {
        $this->RepositoryService->clearCredentials();
    }

    /**
     * 
     * query 
     *
     * @param string repositoryId
     * @param string statement
     * @param boolean searchAllVersions
     * @param boolean includeAllowableActions
     * @param enumIncludeRelationships includeRelationships
     * @param string renditionFilter
     * @param integer maxItems
     * @param integer skipCount
     * @param cmisExtensionType extension
     * @return cmisObjectListType objects
     */
    public function query($repositoryId, $statement, $searchAllVersions = NULL, $includeAllowableActions = NULL, $includeRelationships = NULL, $renditionFilter = NULL, $maxItems = NULL, $skipCount = NULL, $extension = NULL) {
        $parameter = new query();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($statement)) $parameter->statement = (string)$statement;
        if (isset($searchAllVersions)) $parameter->searchAllVersions = $searchAllVersions;
        if (isset($includeAllowableActions)) $parameter->includeAllowableActions = $includeAllowableActions;
        if (isset($includeRelationships)) $parameter->includeRelationships = $includeRelationships;
        if (isset($renditionFilter)) $parameter->renditionFilter = (string)$renditionFilter;
        if (isset($maxItems)) $parameter->maxItems = $maxItems;
        if (isset($skipCount)) $parameter->skipCount = $skipCount;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->DiscoveryService->__soapCall('query', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        $this->decodeObjectProperties ($response->objects->objects);
        return $response->objects;
    }

    /**
     * 
     * getContentChanges 
     *
     * @param string repositoryId
     * @param string changeLogToken
     * @param boolean includeProperties
     * @param string filter
     * @param boolean includePolicyIds
     * @param boolean includeACL
     * @param integer maxItems
     * @param cmisExtensionType extension
     * @return getContentChangesResponse parameters
     */
    public function getContentChanges($repositoryId, $changeLogToken = NULL, $includeProperties = NULL, $filter = NULL, $includePolicyIds = NULL, $includeACL = NULL, $maxItems = NULL, $extension = NULL) {
        $parameter = new getContentChanges();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($changeLogToken)) $parameter->changeLogToken = (string)$changeLogToken;
        if (isset($includeProperties)) $parameter->includeProperties = $includeProperties;
        if (isset($filter)) $parameter->filter = (string)$filter;
        if (isset($includePolicyIds)) $parameter->includePolicyIds = $includePolicyIds;
        if (isset($includeACL)) $parameter->includeACL = $includeACL;
        if (isset($maxItems)) $parameter->maxItems = $maxItems;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->DiscoveryService->__soapCall('getContentChanges', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        $this->decodeObjectProperties ($response->parameters->objects->objects);
        return $response;
    }

    /**
     * 
     * addObjectToFolder 
     *
     * @param string repositoryId
     * @param string objectId
     * @param string folderId
     * @param boolean allVersions
     * @param cmisExtensionType extension
     * @return cmisExtensionType extension
     */
    public function addObjectToFolder($repositoryId, $objectId, $folderId, $allVersions = NULL, $extension = NULL) {
        $parameter = new addObjectToFolder();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($objectId)) $parameter->objectId = (string)$objectId;
        if (isset($folderId)) $parameter->folderId = (string)$folderId;
        if (isset($allVersions)) $parameter->allVersions = $allVersions;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->MultiFilingService->__soapCall('addObjectToFolder', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        return $response->extension;
    }

    /**
     * 
     * removeObjectFromFolder 
     *
     * @param string repositoryId
     * @param string objectId
     * @param string folderId
     * @param cmisExtensionType extension
     * @return cmisExtensionType extension
     */
    public function removeObjectFromFolder($repositoryId, $objectId, $folderId = NULL, $extension = NULL) {
        $parameter = new removeObjectFromFolder();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($objectId)) $parameter->objectId = (string)$objectId;
        if (isset($folderId)) $parameter->folderId = (string)$folderId;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->MultiFilingService->__soapCall('removeObjectFromFolder', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        return $response->extension;
    }

    /**
     * 
     * getDescendants 
     *
     * @param string repositoryId
     * @param string folderId
     * @param integer depth
     * @param string filter
     * @param boolean includeAllowableActions
     * @param enumIncludeRelationships includeRelationships
     * @param string renditionFilter
     * @param boolean includePathSegment
     * @param cmisExtensionType extension
     * @return cmisObjectInFolderContainerType objects
     */
    public function getDescendants($repositoryId, $folderId, $depth = NULL, $filter = NULL, $includeAllowableActions = NULL, $includeRelationships = NULL, $renditionFilter = NULL, $includePathSegment = NULL, $extension = NULL) {
        $parameter = new getDescendants();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($folderId)) $parameter->folderId = (string)$folderId;
        if (isset($depth)) $parameter->depth = $depth;
        if (isset($filter)) $parameter->filter = (string)$filter;
        if (isset($includeAllowableActions)) $parameter->includeAllowableActions = $includeAllowableActions;
        if (isset($includeRelationships)) $parameter->includeRelationships = $includeRelationships;
        if (isset($renditionFilter)) $parameter->renditionFilter = (string)$renditionFilter;
        if (isset($includePathSegment)) $parameter->includePathSegment = $includePathSegment;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->NavigationService->__soapCall('getDescendants', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        $this->decodeObjectInFolderContainerProperties ($response->objects);
        return $response->objects;
    }

    /**
     * 
     * getChildren 
     *
     * @param string repositoryId
     * @param string folderId
     * @param string filter
     * @param string orderBy
     * @param boolean includeAllowableActions
     * @param enumIncludeRelationships includeRelationships
     * @param string renditionFilter
     * @param boolean includePathSegment
     * @param integer maxItems
     * @param integer skipCount
     * @param cmisExtensionType extension
     * @return cmisObjectInFolderListType objects
     */
    public function getChildren($repositoryId, $folderId, $filter = NULL, $orderBy = NULL, $includeAllowableActions = NULL, $includeRelationships = NULL, $renditionFilter = NULL, $includePathSegment = NULL, $maxItems = NULL, $skipCount = NULL, $extension = NULL) {
        $parameter = new getChildren();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($folderId)) $parameter->folderId = (string)$folderId;
        if (isset($filter)) $parameter->filter = (string)$filter;
        if (isset($orderBy)) $parameter->orderBy = (string)$orderBy;
        if (isset($includeAllowableActions)) $parameter->includeAllowableActions = $includeAllowableActions;
        if (isset($includeRelationships)) $parameter->includeRelationships = $includeRelationships;
        if (isset($renditionFilter)) $parameter->renditionFilter = (string)$renditionFilter;
        if (isset($includePathSegment)) $parameter->includePathSegment = $includePathSegment;
        if (isset($maxItems)) $parameter->maxItems = $maxItems;
        if (isset($skipCount)) $parameter->skipCount = $skipCount;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->NavigationService->__soapCall('getChildren', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        $this->decodeObjectInFolderProperties ($response->objects->objects);
        return $response->objects;
    }

    /**
     * 
     * getFolderParent 
     *
     * @param string repositoryId
     * @param string folderId
     * @param string filter
     * @param cmisExtensionType extension
     * @return cmisObjectType object
     */
    public function getFolderParent($repositoryId, $folderId, $filter = NULL, $extension = NULL) {
        $parameter = new getFolderParent();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($folderId)) $parameter->folderId = (string)$folderId;
        if (isset($filter)) $parameter->filter = (string)$filter;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->NavigationService->__soapCall('getFolderParent', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        $this->decodeObjectProperties ($response->object);
        return $response->object;
    }

    /**
     * 
     * getFolderTree 
     *
     * @param string repositoryId
     * @param string folderId
     * @param integer depth
     * @param string filter
     * @param boolean includeAllowableActions
     * @param enumIncludeRelationships includeRelationships
     * @param string renditionFilter
     * @param boolean includePathSegment
     * @param cmisExtensionType extension
     * @return cmisObjectInFolderContainerType objects
     */
    public function getFolderTree($repositoryId, $folderId, $depth = NULL, $filter = NULL, $includeAllowableActions = NULL, $includeRelationships = NULL, $renditionFilter = NULL, $includePathSegment = NULL, $extension = NULL) {
        $parameter = new getFolderTree();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($folderId)) $parameter->folderId = (string)$folderId;
        if (isset($depth)) $parameter->depth = $depth;
        if (isset($filter)) $parameter->filter = (string)$filter;
        if (isset($includeAllowableActions)) $parameter->includeAllowableActions = $includeAllowableActions;
        if (isset($includeRelationships)) $parameter->includeRelationships = $includeRelationships;
        if (isset($renditionFilter)) $parameter->renditionFilter = (string)$renditionFilter;
        if (isset($includePathSegment)) $parameter->includePathSegment = $includePathSegment;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->NavigationService->__soapCall('getFolderTree', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        $this->decodeObjectInFolderContainerProperties ($response->objects);
        return $response->objects;
    }

    /**
     * 
     * getObjectParents 
     *
     * @param string repositoryId
     * @param string objectId
     * @param string filter
     * @param boolean includeAllowableActions
     * @param enumIncludeRelationships includeRelationships
     * @param string renditionFilter
     * @param boolean includeRelativePathSegment
     * @param cmisExtensionType extension
     * @return cmisObjectParentsType parents
     */
    public function getObjectParents($repositoryId, $objectId, $filter = NULL, $includeAllowableActions = NULL, $includeRelationships = NULL, $renditionFilter = NULL, $includeRelativePathSegment = NULL, $extension = NULL) {
        $parameter = new getObjectParents();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($objectId)) $parameter->objectId = (string)$objectId;
        if (isset($filter)) $parameter->filter = (string)$filter;
        if (isset($includeAllowableActions)) $parameter->includeAllowableActions = $includeAllowableActions;
        if (isset($includeRelationships)) $parameter->includeRelationships = $includeRelationships;
        if (isset($renditionFilter)) $parameter->renditionFilter = (string)$renditionFilter;
        if (isset($includeRelativePathSegment)) $parameter->includeRelativePathSegment = $includeRelativePathSegment;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->NavigationService->__soapCall('getObjectParents', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        $this->decodeObjectProperties ($response->parents->object);
        return $response->parents;
    }

    /**
     * 
     * getCheckedOutDocs 
     *
     * @param string repositoryId
     * @param string folderId
     * @param string filter
     * @param string orderBy
     * @param boolean includeAllowableActions
     * @param enumIncludeRelationships includeRelationships
     * @param string renditionFilter
     * @param integer maxItems
     * @param integer skipCount
     * @param cmisExtensionType extension
     * @return cmisObjectListType objects
     */
    public function getCheckedOutDocs($repositoryId, $folderId = NULL, $filter = NULL, $orderBy = NULL, $includeAllowableActions = NULL, $includeRelationships = NULL, $renditionFilter = NULL, $maxItems = NULL, $skipCount = NULL, $extension = NULL) {
        $parameter = new getCheckedOutDocs();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($folderId)) $parameter->folderId = (string)$folderId;
        if (isset($filter)) $parameter->filter = (string)$filter;
        if (isset($orderBy)) $parameter->orderBy = (string)$orderBy;
        if (isset($includeAllowableActions)) $parameter->includeAllowableActions = $includeAllowableActions;
        if (isset($includeRelationships)) $parameter->includeRelationships = $includeRelationships;
        if (isset($renditionFilter)) $parameter->renditionFilter = (string)$renditionFilter;
        if (isset($maxItems)) $parameter->maxItems = $maxItems;
        if (isset($skipCount)) $parameter->skipCount = $skipCount;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->NavigationService->__soapCall('getCheckedOutDocs', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        $this->decodeObjectProperties ($response->objects->objects);
        return $response->objects;
    }

    /**
     * 
     * createDocument 
     *
     * @param string repositoryId
     * @param cmisPropertiesType properties
     * @param string folderId
     * @param cmisContentStreamType contentStream
     * @param enumVersioningState versioningState
     * @param string policies[]
     * @param cmisAccessControlListType addACEs
     * @param cmisAccessControlListType removeACEs
     * @param cmisExtensionType extension
     * @return createDocumentResponse parameters
     */
    public function createDocument($repositoryId, $properties, $folderId = NULL, cmisContentStreamType $contentStream = NULL, $versioningState = NULL, $policies = NULL, cmisAccessControlListType $addACEs = NULL, cmisAccessControlListType $removeACEs = NULL, $extension = NULL) {
        $parameter = new createDocument();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($properties)) $parameter->properties = $this->encodeProperties($properties);
        if (isset($folderId)) $parameter->folderId = (string)$folderId;
        if (isset($contentStream)) $parameter->contentStream = $contentStream;
        if (isset($versioningState)) $parameter->versioningState = $versioningState;
        if (isset($policies)) $parameter->policies = (string)$policies;
        else $parameter->policies = array(); // fix issues with array not set
        if (isset($addACEs)) $parameter->addACEs = $addACEs;
        if (isset($removeACEs)) $parameter->removeACEs = $removeACEs;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->ObjectService->__soapCall('createDocument', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        return $response;
    }

    /**
     * 
     * createDocumentFromSource 
     *
     * @param string repositoryId
     * @param string sourceId
     * @param cmisPropertiesType properties
     * @param string folderId
     * @param enumVersioningState versioningState
     * @param string policies[]
     * @param cmisAccessControlListType addACEs
     * @param cmisAccessControlListType removeACEs
     * @param cmisExtensionType extension
     * @return createDocumentFromSourceResponse parameters
     */
    public function createDocumentFromSource($repositoryId, $sourceId, $properties, $folderId = NULL, $versioningState = NULL, $policies = NULL, cmisAccessControlListType $addACEs = NULL, cmisAccessControlListType $removeACEs = NULL, $extension = NULL) {
        $parameter = new createDocumentFromSource();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($sourceId)) $parameter->sourceId = (string)$sourceId;
        if (isset($properties)) $parameter->properties = $this->encodeProperties($properties);
        if (isset($folderId)) $parameter->folderId = (string)$folderId;
        if (isset($versioningState)) $parameter->versioningState = $versioningState;
        if (isset($policies)) $parameter->policies = (string)$policies;
        else $parameter->policies = array(); // fix issues with array not set
        if (isset($addACEs)) $parameter->addACEs = $addACEs;
        if (isset($removeACEs)) $parameter->removeACEs = $removeACEs;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->ObjectService->__soapCall('createDocumentFromSource', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        return $response;
    }

    /**
     * 
     * createFolder 
     *
     * @param string repositoryId
     * @param cmisPropertiesType properties
     * @param string folderId
     * @param string policies[]
     * @param cmisAccessControlListType addACEs
     * @param cmisAccessControlListType removeACEs
     * @param cmisExtensionType extension
     * @return createFolderResponse parameters
     */
    public function createFolder($repositoryId, $properties, $folderId, $policies = NULL, cmisAccessControlListType $addACEs = NULL, cmisAccessControlListType $removeACEs = NULL, $extension = NULL) {
        $parameter = new createFolder();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($properties)) $parameter->properties = $this->encodeProperties($properties);
        if (isset($folderId)) $parameter->folderId = (string)$folderId;
        if (isset($policies)) $parameter->policies = (string)$policies;
        else $parameter->policies = array(); // fix issues with array not set
        if (isset($addACEs)) $parameter->addACEs = $addACEs;
        if (isset($removeACEs)) $parameter->removeACEs = $removeACEs;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->ObjectService->__soapCall('createFolder', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        return $response;
    }

    /**
     * 
     * createRelationship 
     *
     * @param string repositoryId
     * @param cmisPropertiesType properties
     * @param string policies[]
     * @param cmisAccessControlListType addACEs
     * @param cmisAccessControlListType removeACEs
     * @param cmisExtensionType extension
     * @return createRelationshipResponse parameters
     */
    public function createRelationship($repositoryId, $properties, $policies = NULL, cmisAccessControlListType $addACEs = NULL, cmisAccessControlListType $removeACEs = NULL, $extension = NULL) {
        $parameter = new createRelationship();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($properties)) $parameter->properties = $this->encodeProperties($properties);
        if (isset($policies)) $parameter->policies = (string)$policies;
        else $parameter->policies = array(); // fix issues with array not set
        if (isset($addACEs)) $parameter->addACEs = $addACEs;
        if (isset($removeACEs)) $parameter->removeACEs = $removeACEs;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->ObjectService->__soapCall('createRelationship', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        return $response;
    }

    /**
     * 
     * createPolicy 
     *
     * @param string repositoryId
     * @param cmisPropertiesType properties
     * @param string folderId
     * @param string policies[]
     * @param cmisAccessControlListType addACEs
     * @param cmisAccessControlListType removeACEs
     * @param cmisExtensionType extension
     * @return createPolicyResponse parameters
     */
    public function createPolicy($repositoryId, $properties, $folderId = NULL, $policies = NULL, cmisAccessControlListType $addACEs = NULL, cmisAccessControlListType $removeACEs = NULL, $extension = NULL) {
        $parameter = new createPolicy();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($properties)) $parameter->properties = $this->encodeProperties($properties);
        if (isset($folderId)) $parameter->folderId = (string)$folderId;
        if (isset($policies)) $parameter->policies = (string)$policies;
        else $parameter->policies = array(); // fix issues with array not set
        if (isset($addACEs)) $parameter->addACEs = $addACEs;
        if (isset($removeACEs)) $parameter->removeACEs = $removeACEs;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->ObjectService->__soapCall('createPolicy', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        return $response;
    }

    /**
     * 
     * getAllowableActions 
     *
     * @param string repositoryId
     * @param string objectId
     * @param cmisExtensionType extension
     * @return cmisAllowableActionsType allowableActions
     */
    public function getAllowableActions($repositoryId, $objectId, $extension = NULL) {
        $parameter = new getAllowableActions();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($objectId)) $parameter->objectId = (string)$objectId;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->ObjectService->__soapCall('getAllowableActions', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        return $response->allowableActions;
    }

    /**
     * 
     * getObject 
     *
     * @param string repositoryId
     * @param string objectId
     * @param string filter
     * @param boolean includeAllowableActions
     * @param enumIncludeRelationships includeRelationships
     * @param string renditionFilter
     * @param boolean includePolicyIds
     * @param boolean includeACL
     * @param cmisExtensionType extension
     * @return cmisObjectType object
     */
    public function getObject($repositoryId, $objectId, $filter = NULL, $includeAllowableActions = NULL, $includeRelationships = NULL, $renditionFilter = NULL, $includePolicyIds = NULL, $includeACL = NULL, $extension = NULL) {
        $parameter = new getObject();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($objectId)) $parameter->objectId = (string)$objectId;
        if (isset($filter)) $parameter->filter = (string)$filter;
        if (isset($includeAllowableActions)) $parameter->includeAllowableActions = $includeAllowableActions;
        if (isset($includeRelationships)) $parameter->includeRelationships = $includeRelationships;
        if (isset($renditionFilter)) $parameter->renditionFilter = (string)$renditionFilter;
        if (isset($includePolicyIds)) $parameter->includePolicyIds = $includePolicyIds;
        if (isset($includeACL)) $parameter->includeACL = $includeACL;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->ObjectService->__soapCall('getObject', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        $this->decodeObjectProperties ($response->object);
        return $response->object;
    }

    /**
     * 
     * getProperties 
     *
     * @param string repositoryId
     * @param string objectId
     * @param string filter
     * @param cmisExtensionType extension
     * @return cmisPropertiesType properties
     */
    public function getProperties($repositoryId, $objectId, $filter = NULL, $extension = NULL) {
        $parameter = new getProperties();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($objectId)) $parameter->objectId = (string)$objectId;
        if (isset($filter)) $parameter->filter = (string)$filter;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->ObjectService->__soapCall('getProperties', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        $this->decodeProperties ($response->properties);
        return $response->properties;
    }

    /**
     * 
     * getRenditions 
     *
     * @param string repositoryId
     * @param string objectId
     * @param string renditionFilter
     * @param integer maxItems
     * @param integer skipCount
     * @param cmisExtensionType extension
     * @return cmisRenditionType renditions
     */
    public function getRenditions($repositoryId, $objectId, $renditionFilter = NULL, $maxItems = NULL, $skipCount = NULL, $extension = NULL) {
        $parameter = new getRenditions();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($objectId)) $parameter->objectId = (string)$objectId;
        if (isset($renditionFilter)) $parameter->renditionFilter = (string)$renditionFilter;
        if (isset($maxItems)) $parameter->maxItems = $maxItems;
        if (isset($skipCount)) $parameter->skipCount = $skipCount;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->ObjectService->__soapCall('getRenditions', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        return $response->renditions;
    }

    /**
     * 
     * getObjectByPath 
     *
     * @param string repositoryId
     * @param string path
     * @param string filter
     * @param boolean includeAllowableActions
     * @param enumIncludeRelationships includeRelationships
     * @param string renditionFilter
     * @param boolean includePolicyIds
     * @param boolean includeACL
     * @param cmisExtensionType extension
     * @return cmisObjectType object
     */
    public function getObjectByPath($repositoryId, $path, $filter = NULL, $includeAllowableActions = NULL, $includeRelationships = NULL, $renditionFilter = NULL, $includePolicyIds = NULL, $includeACL = NULL, $extension = NULL) {
        $parameter = new getObjectByPath();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($path)) $parameter->path = (string)$path;
        if (isset($filter)) $parameter->filter = (string)$filter;
        if (isset($includeAllowableActions)) $parameter->includeAllowableActions = $includeAllowableActions;
        if (isset($includeRelationships)) $parameter->includeRelationships = $includeRelationships;
        if (isset($renditionFilter)) $parameter->renditionFilter = (string)$renditionFilter;
        if (isset($includePolicyIds)) $parameter->includePolicyIds = $includePolicyIds;
        if (isset($includeACL)) $parameter->includeACL = $includeACL;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->ObjectService->__soapCall('getObjectByPath', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        $this->decodeObjectProperties ($response->object);
        return $response->object;
    }

    /**
     * 
     * getContentStream 
     *
     * @param string repositoryId
     * @param string objectId
     * @param string streamId
     * @param integer offset
     * @param integer length
     * @param cmisExtensionType extension
     * @return cmisContentStreamType contentStream
     */
    public function getContentStream($repositoryId, $objectId, $streamId = NULL, $offset = NULL, $length = NULL, $extension = NULL) {
        $parameter = new getContentStream();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($objectId)) $parameter->objectId = (string)$objectId;
        if (isset($streamId)) $parameter->streamId = (string)$streamId;
        if (isset($offset)) $parameter->offset = $offset;
        if (isset($length)) $parameter->length = $length;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->ObjectService->__soapCall('getContentStream', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        return $response->contentStream;
    }

    /**
     * 
     * updateProperties 
     *
     * @param string repositoryId
     * @param string objectId
     * @param string changeToken
     * @param cmisPropertiesType properties
     * @param cmisExtensionType extension
     * @return updatePropertiesResponse parameters
     */
    public function updateProperties($repositoryId, $objectId, $changeToken = NULL, $properties, $extension = NULL) {
        $parameter = new updateProperties();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($objectId)) $parameter->objectId = (string)$objectId;
        if (isset($changeToken)) $parameter->changeToken = (string)$changeToken;
        if (isset($properties)) $parameter->properties = $this->encodeProperties($properties);
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->ObjectService->__soapCall('updateProperties', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        return $response;
    }

    /**
     * 
     * moveObject 
     *
     * @param string repositoryId
     * @param string objectId
     * @param string targetFolderId
     * @param string sourceFolderId
     * @param cmisExtensionType extension
     * @return moveObjectResponse parameters
     */
    public function moveObject($repositoryId, $objectId, $targetFolderId, $sourceFolderId, $extension = NULL) {
        $parameter = new moveObject();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($objectId)) $parameter->objectId = (string)$objectId;
        if (isset($targetFolderId)) $parameter->targetFolderId = (string)$targetFolderId;
        if (isset($sourceFolderId)) $parameter->sourceFolderId = (string)$sourceFolderId;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->ObjectService->__soapCall('moveObject', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        return $response;
    }

    /**
     * 
     * deleteObject 
     *
     * @param string repositoryId
     * @param string objectId
     * @param boolean allVersions
     * @param cmisExtensionType extension
     * @return cmisExtensionType extension
     */
    public function deleteObject($repositoryId, $objectId, $allVersions = NULL, $extension = NULL) {
        $parameter = new deleteObject();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($objectId)) $parameter->objectId = (string)$objectId;
        if (isset($allVersions)) $parameter->allVersions = $allVersions;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->ObjectService->__soapCall('deleteObject', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        return $response->extension;
    }

    /**
     * 
     * deleteTree 
     *
     * @param string repositoryId
     * @param string folderId
     * @param boolean allVersions
     * @param enumUnfileObject unfileObjects
     * @param boolean continueOnFailure
     * @param cmisExtensionType extension
     * @return failedToDelete failedToDelete
     */
    public function deleteTree($repositoryId, $folderId, $allVersions = NULL, $unfileObjects = NULL, $continueOnFailure = NULL, $extension = NULL) {
        $parameter = new deleteTree();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($folderId)) $parameter->folderId = (string)$folderId;
        if (isset($allVersions)) $parameter->allVersions = $allVersions;
        if (isset($unfileObjects)) $parameter->unfileObjects = $unfileObjects;
        if (isset($continueOnFailure)) $parameter->continueOnFailure = $continueOnFailure;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->ObjectService->__soapCall('deleteTree', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        return $response->failedToDelete;
    }

    /**
     * 
     * setContentStream 
     *
     * @param string repositoryId
     * @param string objectId
     * @param boolean overwriteFlag
     * @param string changeToken
     * @param cmisContentStreamType contentStream
     * @param cmisExtensionType extension
     * @return setContentStreamResponse parameters
     */
    public function setContentStream($repositoryId, $objectId, $overwriteFlag = NULL, $changeToken = NULL, cmisContentStreamType $contentStream, $extension = NULL) {
        $parameter = new setContentStream();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($objectId)) $parameter->objectId = (string)$objectId;
        if (isset($overwriteFlag)) $parameter->overwriteFlag = $overwriteFlag;
        if (isset($changeToken)) $parameter->changeToken = (string)$changeToken;
        if (isset($contentStream)) $parameter->contentStream = $contentStream;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->ObjectService->__soapCall('setContentStream', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        return $response;
    }

    /**
     * 
     * deleteContentStream 
     *
     * @param string repositoryId
     * @param string objectId
     * @param string changeToken
     * @param cmisExtensionType extension
     * @return deleteContentStreamResponse parameters
     */
    public function deleteContentStream($repositoryId, $objectId, $changeToken = NULL, $extension = NULL) {
        $parameter = new deleteContentStream();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($objectId)) $parameter->objectId = (string)$objectId;
        if (isset($changeToken)) $parameter->changeToken = (string)$changeToken;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->ObjectService->__soapCall('deleteContentStream', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        return $response;
    }

    /**
     * 
     * applyPolicy 
     *
     * @param string repositoryId
     * @param string policyId
     * @param string objectId
     * @param cmisExtensionType extension
     * @return cmisExtensionType extension
     */
    public function applyPolicy($repositoryId, $policyId, $objectId, $extension = NULL) {
        $parameter = new applyPolicy();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($policyId)) $parameter->policyId = (string)$policyId;
        if (isset($objectId)) $parameter->objectId = (string)$objectId;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->PolicyService->__soapCall('applyPolicy', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        return $response->extension;
    }

    /**
     * 
     * removePolicy 
     *
     * @param string repositoryId
     * @param string policyId
     * @param string objectId
     * @param cmisExtensionType extension
     * @return cmisExtensionType extension
     */
    public function removePolicy($repositoryId, $policyId, $objectId, $extension = NULL) {
        $parameter = new removePolicy();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($policyId)) $parameter->policyId = (string)$policyId;
        if (isset($objectId)) $parameter->objectId = (string)$objectId;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->PolicyService->__soapCall('removePolicy', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        return $response->extension;
    }

    /**
     * 
     * getAppliedPolicies 
     *
     * @param string repositoryId
     * @param string objectId
     * @param string filter
     * @param cmisExtensionType extension
     * @return cmisObjectType objects
     */
    public function getAppliedPolicies($repositoryId, $objectId, $filter = NULL, $extension = NULL) {
        $parameter = new getAppliedPolicies();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($objectId)) $parameter->objectId = (string)$objectId;
        if (isset($filter)) $parameter->filter = (string)$filter;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->PolicyService->__soapCall('getAppliedPolicies', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        $this->decodeObjectProperties ($response->objects);
        return $response->objects;
    }

    /**
     * 
     * getObjectRelationships 
     *
     * @param string repositoryId
     * @param string objectId
     * @param boolean includeSubRelationshipTypes
     * @param enumRelationshipDirection relationshipDirection
     * @param string typeId
     * @param string filter
     * @param boolean includeAllowableActions
     * @param integer maxItems
     * @param integer skipCount
     * @param cmisExtensionType extension
     * @return cmisObjectListType objects
     */
    public function getObjectRelationships($repositoryId, $objectId, $includeSubRelationshipTypes = NULL, $relationshipDirection = NULL, $typeId = NULL, $filter = NULL, $includeAllowableActions = NULL, $maxItems = NULL, $skipCount = NULL, $extension = NULL) {
        $parameter = new getObjectRelationships();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($objectId)) $parameter->objectId = (string)$objectId;
        if (isset($includeSubRelationshipTypes)) $parameter->includeSubRelationshipTypes = $includeSubRelationshipTypes;
        if (isset($relationshipDirection)) $parameter->relationshipDirection = $relationshipDirection;
        if (isset($typeId)) $parameter->typeId = (string)$typeId;
        if (isset($filter)) $parameter->filter = (string)$filter;
        if (isset($includeAllowableActions)) $parameter->includeAllowableActions = $includeAllowableActions;
        if (isset($maxItems)) $parameter->maxItems = $maxItems;
        if (isset($skipCount)) $parameter->skipCount = $skipCount;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->RelationshipService->__soapCall('getObjectRelationships', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        $this->decodeObjectProperties ($response->objects->objects);
        return $response->objects;
    }

    /**
     * 
     * getRepositories 
     *
     * @param cmisExtensionType extension
     * @return cmisRepositoryEntryType repositories
     */
    public function getRepositories($extension = NULL) {
        $parameter = new getRepositories();
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->RepositoryService->__soapCall('getRepositories', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        return $response->repositories;
    }

    /**
     * 
     * getRepositoryInfo 
     *
     * @param string repositoryId
     * @param cmisExtensionType extension
     * @return cmisRepositoryInfoType repositoryInfo
     */
    public function getRepositoryInfo($repositoryId, $extension = NULL) {
        $parameter = new getRepositoryInfo();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->RepositoryService->__soapCall('getRepositoryInfo', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        return $response->repositoryInfo;
    }

    /**
     * 
     * getTypeChildren 
     *
     * @param string repositoryId
     * @param string typeId
     * @param boolean includePropertyDefinitions
     * @param integer maxItems
     * @param integer skipCount
     * @param cmisExtensionType extension
     * @return cmisTypeDefinitionListType types
     */
    public function getTypeChildren($repositoryId, $typeId = NULL, $includePropertyDefinitions = NULL, $maxItems = NULL, $skipCount = NULL, $extension = NULL) {
        $parameter = new getTypeChildren();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($typeId)) $parameter->typeId = (string)$typeId;
        if (isset($includePropertyDefinitions)) $parameter->includePropertyDefinitions = $includePropertyDefinitions;
        if (isset($maxItems)) $parameter->maxItems = $maxItems;
        if (isset($skipCount)) $parameter->skipCount = $skipCount;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->RepositoryService->__soapCall('getTypeChildren', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        return $response->types;
    }

    /**
     * 
     * getTypeDescendants 
     *
     * @param string repositoryId
     * @param string typeId
     * @param integer depth
     * @param boolean includePropertyDefinitions
     * @param cmisExtensionType extension
     * @return cmisTypeContainer types
     */
    public function getTypeDescendants($repositoryId, $typeId = NULL, $depth = NULL, $includePropertyDefinitions = NULL, $extension = NULL) {
        $parameter = new getTypeDescendants();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($typeId)) $parameter->typeId = (string)$typeId;
        if (isset($depth)) $parameter->depth = $depth;
        if (isset($includePropertyDefinitions)) $parameter->includePropertyDefinitions = $includePropertyDefinitions;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->RepositoryService->__soapCall('getTypeDescendants', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        return $response->types;
    }

    /**
     * 
     * getTypeDefinition 
     *
     * @param string repositoryId
     * @param string typeId
     * @param cmisExtensionType extension
     * @return cmisTypeDefinitionType type
     */
    public function getTypeDefinition($repositoryId, $typeId, $extension = NULL) {
        $parameter = new getTypeDefinition();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($typeId)) $parameter->typeId = (string)$typeId;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->RepositoryService->__soapCall('getTypeDefinition', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        return $response->type;
    }

    /**
     * 
     * checkOut 
     *
     * @param string repositoryId
     * @param string objectId
     * @param cmisExtensionType extension
     * @return checkOutResponse parameters
     */
    public function checkOut($repositoryId, $objectId, $extension = NULL) {
        $parameter = new checkOut();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($objectId)) $parameter->objectId = (string)$objectId;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->VersioningService->__soapCall('checkOut', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        return $response;
    }

    /**
     * 
     * cancelCheckOut 
     *
     * @param string repositoryId
     * @param string objectId
     * @param cmisExtensionType extension
     * @return cmisExtensionType extension
     */
    public function cancelCheckOut($repositoryId, $objectId, $extension = NULL) {
        $parameter = new cancelCheckOut();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($objectId)) $parameter->objectId = (string)$objectId;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->VersioningService->__soapCall('cancelCheckOut', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        return $response->extension;
    }

    /**
     * 
     * checkIn 
     *
     * @param string repositoryId
     * @param string objectId
     * @param boolean major
     * @param cmisPropertiesType properties
     * @param cmisContentStreamType contentStream
     * @param string checkinComment
     * @param string policies[]
     * @param cmisAccessControlListType addACEs
     * @param cmisAccessControlListType removeACEs
     * @param cmisExtensionType extension
     * @return checkInResponse parameters
     */
    public function checkIn($repositoryId, $objectId, $major = NULL, $properties = NULL, cmisContentStreamType $contentStream = NULL, $checkinComment = NULL, $policies = NULL, cmisAccessControlListType $addACEs = NULL, cmisAccessControlListType $removeACEs = NULL, $extension = NULL) {
        $parameter = new checkIn();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($objectId)) $parameter->objectId = (string)$objectId;
        if (isset($major)) $parameter->major = $major;
        if (isset($properties)) $parameter->properties = $this->encodeProperties($properties);
        if (isset($contentStream)) $parameter->contentStream = $contentStream;
        if (isset($checkinComment)) $parameter->checkinComment = (string)$checkinComment;
        if (isset($policies)) $parameter->policies = (string)$policies;
        else $parameter->policies = array(); // fix issues with array not set
        if (isset($addACEs)) $parameter->addACEs = $addACEs;
        if (isset($removeACEs)) $parameter->removeACEs = $removeACEs;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->VersioningService->__soapCall('checkIn', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        return $response;
    }

    /**
     * 
     * getObjectOfLatestVersion 
     *
     * @param string repositoryId
     * @param string objectId
     * @param boolean major
     * @param string filter
     * @param boolean includeAllowableActions
     * @param enumIncludeRelationships includeRelationships
     * @param string renditionFilter
     * @param boolean includePolicyIds
     * @param boolean includeACL
     * @param cmisExtensionType extension
     * @return cmisObjectType object
     */
    public function getObjectOfLatestVersion($repositoryId, $objectId, $major = NULL, $filter = NULL, $includeAllowableActions = NULL, $includeRelationships = NULL, $renditionFilter = NULL, $includePolicyIds = NULL, $includeACL = NULL, $extension = NULL) {
        $parameter = new getObjectOfLatestVersion();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($objectId)) $parameter->objectId = (string)$objectId;
        if (isset($major)) $parameter->major = $major;
        if (isset($filter)) $parameter->filter = (string)$filter;
        if (isset($includeAllowableActions)) $parameter->includeAllowableActions = $includeAllowableActions;
        if (isset($includeRelationships)) $parameter->includeRelationships = $includeRelationships;
        if (isset($renditionFilter)) $parameter->renditionFilter = (string)$renditionFilter;
        if (isset($includePolicyIds)) $parameter->includePolicyIds = $includePolicyIds;
        if (isset($includeACL)) $parameter->includeACL = $includeACL;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->VersioningService->__soapCall('getObjectOfLatestVersion', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        $this->decodeObjectProperties ($response->object);
        return $response->object;
    }

    /**
     * 
     * getPropertiesOfLatestVersion 
     *
     * @param string repositoryId
     * @param string objectId
     * @param boolean major
     * @param string filter
     * @param cmisExtensionType extension
     * @return cmisPropertiesType properties
     */
    public function getPropertiesOfLatestVersion($repositoryId, $objectId, $major = NULL, $filter = NULL, $extension = NULL) {
        $parameter = new getPropertiesOfLatestVersion();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($objectId)) $parameter->objectId = (string)$objectId;
        if (isset($major)) $parameter->major = $major;
        if (isset($filter)) $parameter->filter = (string)$filter;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->VersioningService->__soapCall('getPropertiesOfLatestVersion', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        $this->decodeProperties ($response->properties);
        return $response->properties;
    }

    /**
     * 
     * getAllVersions 
     *
     * @param string repositoryId
     * @param string objectId
     * @param string filter
     * @param boolean includeAllowableActions
     * @param cmisExtensionType extension
     * @return cmisObjectType objects
     */
    public function getAllVersions($repositoryId, $objectId, $filter = NULL, $includeAllowableActions = NULL, $extension = NULL) {
        $parameter = new getAllVersions();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($objectId)) $parameter->objectId = (string)$objectId;
        if (isset($filter)) $parameter->filter = (string)$filter;
        if (isset($includeAllowableActions)) $parameter->includeAllowableActions = $includeAllowableActions;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->VersioningService->__soapCall('getAllVersions', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        $this->decodeObjectProperties ($response->objects);
        return $response->objects;
    }

    /**
     * 
     * getACL 
     *
     * @param string repositoryId
     * @param string objectId
     * @param boolean onlyBasicPermissions
     * @param cmisExtensionType extension
     * @return cmisACLType ACL
     */
    public function getACL($repositoryId, $objectId, $onlyBasicPermissions = NULL, $extension = NULL) {
        $parameter = new getACL();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($objectId)) $parameter->objectId = (string)$objectId;
        if (isset($onlyBasicPermissions)) $parameter->onlyBasicPermissions = $onlyBasicPermissions;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->ACLService->__soapCall('getACL', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        return $response->ACL;
    }

    /**
     * 
     * applyACL 
     *
     * @param string repositoryId
     * @param string objectId
     * @param cmisAccessControlListType addACEs
     * @param cmisAccessControlListType removeACEs
     * @param enumACLPropagation ACLPropagation
     * @param cmisExtensionType extension
     * @return cmisACLType ACL
     */
    public function applyACL($repositoryId, $objectId, cmisAccessControlListType $addACEs = NULL, cmisAccessControlListType $removeACEs = NULL, $ACLPropagation = NULL, $extension = NULL) {
        $parameter = new applyACL();
        if (isset($repositoryId)) $parameter->repositoryId = (string)$repositoryId;
        if (isset($objectId)) $parameter->objectId = (string)$objectId;
        if (isset($addACEs)) $parameter->addACEs = $addACEs;
        if (isset($removeACEs)) $parameter->removeACEs = $removeACEs;
        if (isset($ACLPropagation)) $parameter->ACLPropagation = $ACLPropagation;
        if (isset($extension)) $parameter->extension = $extension;

        $parameter = new SoapVar($parameter, SOAP_ENC_OBJECT);

        try {
            $response = $this->ACLService->__soapCall('applyACL', array($parameter));
        }
        catch (SoapFault $sf) {
            if (function_exists($this->errorFunction)) {
                $function = $this->errorFunction;
                $function($sf);
            } else {
                echo "\nSoapFault ERROR: " . $sf->getMessage() . "\n";
            }
        }
        return $response->ACL;
    }



    /**
     * Convert from propertiesArray (one array with named properties)
     * to cmisPropertiesType (individual arrays per type)
     * 
     * @param array $propertiesArray
     * @return \cmisPropertiesType 
     */
    private function encodeProperties($propertiesArray) {
        $propertiesObject = new cmisPropertiesType;
        foreach (get_class_vars('cmisPropertiesType') as $propertyType => $propertyDefault) {
            $propertiesObject->$propertyType = array(); // initialize empty array to avoid nil values
        }
        foreach ($propertiesArray as $propertyName => $propertyValue) {   // loop over all properties
            if (is_object($propertyValue)) {                           // passed as property object
                $propertyType = get_class($propertyValue);             // find out type,
                if (substr($propertyType, 0, 12) == 'cmisProperty') {  // ensure it's a property type
                    $propertyField = 'property' . substr($propertyType, 12);    // get name of array
                    $propertyValue->propertyDefinitionId = $propertyName;       // assign property name
                    array_push($propertiesObject->$propertyField, $propertyValue); // and value
                } else {
                    $propertyValue->value = (string)$propertyValue;    // other object, convert to string
                }                                                      // and assign
            }
            if (!is_object($propertyValue)) {                          // take all other properties as string
                $property = new propertyString($propertyValue);                      // create and fill string property
                $property->propertyDefinitionId = $propertyName;
                array_push($propertiesObject->propertyString, $property); // and assign it
            }
        }
        return $propertiesObject;
    }

    /**
     * Convert from cmisPropertiesType (object with individual arrays per type=
     * to one properties array with named property objects
     * 
     * @param cmisPropertiesType $propertiesObject
     * @return array properties
     */
    private function decodeProperties(&$propertiesObject) {
        if (!isset($propertiesObject)) return;
        $propertiesArray = array();
        foreach (get_object_vars($propertiesObject) as $propertyType => $propertyValues) { // loop over all types
            if (!is_array($propertyValues)) continue;                 // skip empty types
            if ($propertyType == 'any') continue;                     // skip any, eg aspects from Alfresco for now
            foreach ($propertyValues as $propertyValue) {            // loop over all properties of this type
                $propertiesArray[$propertyValue->propertyDefinitionId] = $propertyValue; // fill array with name and value
            }
        }
        $propertiesObject = $propertiesArray;
    }
    
    private function decodeObjectProperties(&$objects) {
        if (!isset($objects)) return;
        if (is_array($objects)) {
            foreach ($objects as $object) {
                $this->decodeProperties($object->properties);
                if (isset($object->relationships)) {
                    $this->decodeObjectProperties($object->relationships);
                }
            }
        }
        else {
            $this->decodeProperties($objects->properties);
            if (isset($objects->relationships)) {
                $this->decodeObjectProperties($objects->relationships);
            }
        }
    }
    
    private function decodeObjectInFolderProperties(&$objects) {
        if (!isset($objects)) return;
        if (is_array($objects)) {
            foreach ($objects as $object) {
                $this->decodeProperties($object->object->properties);
            }
        }
        else {
            $this->decodeProperties($objects->object->properties);
        }
    }

    private function decodeObjectInFolderContainerProperties($objects) {
        if (!isset($objects)) return;
        if (is_array($objects)) {
            foreach ($objects as $object) {
                $this->decodeProperties($object->objectInFolder->object->properties);
                if (isset($object->children)) {
                    $this->decodeObjectInFolderContainerProperties($object->children);
                }
            }
        }
        else {
            $this->decodeProperties($objects->objectInFolder->object->properties);
            if (isset($objects->children)) {
                $this->decodeObjectInFolderContainerProperties($objects->children);
            }
        }
    }
}


?>
