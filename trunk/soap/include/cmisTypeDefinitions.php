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
 * CMIS Type Definitions
 * 
 * Type definitions for the CMIS object model, to support the Web Services binding for PHP,
 * as published by OASIS (http://www.oasis-open.org/committees/cmis/)
 *
 * @author    Karsten Eberding
 * @package   CMISWebService
 * @version   0.5
 */

class enumDecimalPrecision {
  const value_32 = '32';
  const value_64 = '64';
}

class enumContentStreamAllowed {
  const notallowed = 'notallowed';
  const allowed = 'allowed';
  const required = 'required';
}

class enumCardinality {
  const single = 'single';
  const multi = 'multi';
}

class enumUpdatability {
  const readonly = 'readonly';
  const readwrite = 'readwrite';
  const whencheckedout = 'whencheckedout';
  const oncreate = 'oncreate';
}

class enumDateTimeResolution {
  const year = 'year';
  const date = 'date';
  const time = 'time';
}

class enumPropertyType {
  const boolean = 'boolean';
  const id = 'id';
  const integer = 'integer';
  const datetime = 'datetime';
  const decimal = 'decimal';
  const html = 'html';
  const string = 'string';
  const uri = 'uri';
}

class enumBaseObjectTypeIds {
  const cmis_document = 'cmis:document';
  const cmis_folder = 'cmis:folder';
  const cmis_relationship = 'cmis:relationship';
  const cmis_policy = 'cmis:policy';
}

class enumCapabilityQuery {
  const none = 'none';
  const metadataonly = 'metadataonly';
  const fulltextonly = 'fulltextonly';
  const bothseparate = 'bothseparate';
  const bothcombined = 'bothcombined';
}

class enumCapabilityJoin {
  const none = 'none';
  const inneronly = 'inneronly';
  const innerandouter = 'innerandouter';
}

class enumCapabilityContentStreamUpdates {
  const anytime = 'anytime';
  const pwconly = 'pwconly';
  const none = 'none';
}

class enumVersioningState {
  const none = 'none';
  const checkedout = 'checkedout';
  const minor = 'minor';
  const major = 'major';
}

class enumUnfileObject {
  const unfile = 'unfile';
  const deletesinglefiled = 'deletesinglefiled';
  const delete = 'delete';
}

class enumRelationshipDirection {
  const source = 'source';
  const target = 'target';
  const either = 'either';
}

class enumIncludeRelationships {
  const none = 'none';
  const source = 'source';
  const target = 'target';
  const both = 'both';
}

class enumPropertiesBase {
  const cmis_name = 'cmis:name';
  const cmis_objectId = 'cmis:objectId';
  const cmis_objectTypeId = 'cmis:objectTypeId';
  const cmis_baseTypeId = 'cmis:baseTypeId';
  const cmis_createdBy = 'cmis:createdBy';
  const cmis_creationDate = 'cmis:creationDate';
  const cmis_lastModifiedBy = 'cmis:lastModifiedBy';
  const cmis_lastModificationDate = 'cmis:lastModificationDate';
  const cmis_changeToken = 'cmis:changeToken';
}

class enumPropertiesDocument {
  const cmis_isImmutable = 'cmis:isImmutable';
  const cmis_isLatestVersion = 'cmis:isLatestVersion';
  const cmis_isMajorVersion = 'cmis:isMajorVersion';
  const cmis_isLatestMajorVersion = 'cmis:isLatestMajorVersion';
  const cmis_versionLabel = 'cmis:versionLabel';
  const cmis_versionSeriesId = 'cmis:versionSeriesId';
  const cmis_isVersionSeriesCheckedOut = 'cmis:isVersionSeriesCheckedOut';
  const cmis_versionSeriesCheckedOutBy = 'cmis:versionSeriesCheckedOutBy';
  const cmis_versionSeriesCheckedOutId = 'cmis:versionSeriesCheckedOutId';
  const cmis_checkinComment = 'cmis:checkinComment';
  const cmis_contentStreamLength = 'cmis:contentStreamLength';
  const cmis_contentStreamMimeType = 'cmis:contentStreamMimeType';
  const cmis_contentStreamFileName = 'cmis:contentStreamFileName';
  const cmis_contentStreamId = 'cmis:contentStreamId';
}

class enumPropertiesFolder {
  const cmis_parentId = 'cmis:parentId';
  const cmis_allowedChildObjectTypeIds = 'cmis:allowedChildObjectTypeIds';
  const cmis_path = 'cmis:path';
}

class enumPropertiesRelationship {
  const cmis_sourceId = 'cmis:sourceId';
  const cmis_targetId = 'cmis:targetId';
}

class enumPropertiesPolicy {
  const cmis_policyText = 'cmis:policyText';
}

class cmisObjectType {
  public $properties; // cmisPropertiesType
  public $allowableActions; // cmisAllowableActionsType
  public $relationship; // cmisObjectType
  public $changeEventInfo; // cmisChangeEventType
  public $acl; // cmisAccessControlListType
  public $exactACL; // boolean
  public $policyIds; // cmisListOfIdsType
  public $rendition; // cmisRenditionType
}

class cmisPropertiesType {
  public $propertyBoolean; // cmisPropertyBoolean
  public $propertyId; // cmisPropertyId
  public $propertyInteger; // cmisPropertyInteger
  public $propertyDateTime; // cmisPropertyDateTime
  public $propertyDecimal; // cmisPropertyDecimal
  public $propertyHtml; // cmisPropertyHtml
  public $propertyString; // cmisPropertyString
  public $propertyUri; // cmisPropertyUri
}

class cmisProperty {
    
    public $value;

    public function __construct($v) {
        if (is_array($v)) $this->value = $v;
        else $this->value = array($v);
    }
    
    public function __toString() {
        if (isset($this->value[0])) return $this->value[0];
        else return (NULL);
    }
    
    public function single() {
        if (isset($this->value[0])) return $this->value[0];
        else return (NULL);
    }
    
    public function multi() {
        if (isset($this->value)) return $this->value;
        else return (NULL);
    }
}

class cmisPropertyBoolean extends cmisProperty {
}

class cmisPropertyId extends cmisProperty {
}

class cmisPropertyInteger extends cmisProperty {
}

class cmisPropertyDateTime extends cmisProperty {
}

class cmisPropertyDecimal extends cmisProperty {
}

class cmisPropertyHtml extends cmisProperty {
}

class cmisPropertyString extends cmisProperty {
}

class cmisPropertyUri extends cmisProperty {
}

class cmisChoice {
}

class cmisChoiceBoolean extends cmisChoice {
  public $value; // boolean
  public $choice; // cmisChoiceBoolean
}

class cmisChoiceId extends cmisChoice {
  public $value; // string
  public $choice; // cmisChoiceId
}

class cmisChoiceInteger extends cmisChoice {
  public $value; // integer
  public $choice; // cmisChoiceInteger
}

class cmisChoiceDateTime extends cmisChoice {
  public $value; // dateTime
  public $choice; // cmisChoiceDateTime
}

class cmisChoiceDecimal extends cmisChoice {
  public $value; // decimal
  public $choice; // cmisChoiceDecimal
}

class cmisChoiceHtml extends cmisChoice {
  public $value; // string
  public $choice; // cmisChoiceHtml
}

class cmisChoiceString extends cmisChoice {
  public $value; // string
  public $choice; // cmisChoiceString
}

class cmisChoiceUri extends cmisChoice {
  public $value; // anyURI
  public $choice; // cmisChoiceUri
}

class cmisAllowableActionsType {
  public $canDeleteObject; // boolean
  public $canUpdateProperties; // boolean
  public $canGetFolderTree; // boolean
  public $canGetProperties; // boolean
  public $canGetObjectRelationships; // boolean
  public $canGetObjectParents; // boolean
  public $canGetFolderParent; // boolean
  public $canGetDescendants; // boolean
  public $canMoveObject; // boolean
  public $canDeleteContentStream; // boolean
  public $canCheckOut; // boolean
  public $canCancelCheckOut; // boolean
  public $canCheckIn; // boolean
  public $canSetContentStream; // boolean
  public $canGetAllVersions; // boolean
  public $canAddObjectToFolder; // boolean
  public $canRemoveObjectFromFolder; // boolean
  public $canGetContentStream; // boolean
  public $canApplyPolicy; // boolean
  public $canGetAppliedPolicies; // boolean
  public $canRemovePolicy; // boolean
  public $canGetChildren; // boolean
  public $canCreateDocument; // boolean
  public $canCreateFolder; // boolean
  public $canCreateRelationship; // boolean
  public $canDeleteTree; // boolean
  public $canGetRenditions; // boolean
  public $canGetACL; // boolean
  public $canApplyACL; // boolean
}

class cmisListOfIdsType {
  public $id; // string
}

class cmisPropertyDefinitionType {
  public $id; // string
  public $localName; // string
  public $localNamespace; // anyURI
  public $displayName; // string
  public $queryName; // string
  public $description; // string
  public $propertyType; // enumPropertyType
  public $cardinality; // enumCardinality
  public $updatability; // enumUpdatability
  public $inherited; // boolean
  public $required; // boolean
  public $queryable; // boolean
  public $orderable; // boolean
  public $openChoice; // boolean
}

class cmisPropertyBooleanDefinitionType extends cmisPropertyDefinitionType {
  public $defaultValue; // cmisPropertyBoolean
  public $choice; // cmisChoiceBoolean
}

class cmisPropertyIdDefinitionType extends cmisPropertyDefinitionType {
  public $defaultValue; // cmisPropertyId
  public $choice; // cmisChoiceId
}

class cmisPropertyIntegerDefinitionType extends cmisPropertyDefinitionType {
  public $defaultValue; // cmisPropertyInteger
  public $maxValue; // integer
  public $minValue; // integer
  public $choice; // cmisChoiceInteger
}

class cmisPropertyDateTimeDefinitionType extends cmisPropertyDefinitionType {
  public $defaultValue; // cmisPropertyDateTime
  public $resolution; // enumDateTimeResolution
  public $choice; // cmisChoiceDateTime
}

class cmisPropertyDecimalDefinitionType extends cmisPropertyDefinitionType {
  public $defaultValue; // cmisPropertyDecimal
  public $maxValue; // decimal
  public $minValue; // decimal
  public $precision; // enumDecimalPrecision
  public $choice; // cmisChoiceDecimal
}

class cmisPropertyHtmlDefinitionType extends cmisPropertyDefinitionType {
  public $defaultValue; // cmisPropertyHtml
  public $choice; // cmisChoiceHtml
}

class cmisPropertyStringDefinitionType extends cmisPropertyDefinitionType {
  public $defaultValue; // cmisPropertyString
  public $maxLength; // integer
  public $choice; // cmisChoiceString
}

class cmisPropertyUriDefinitionType extends cmisPropertyDefinitionType {
  public $defaultValue; // cmisPropertyUri
  public $choice; // cmisChoiceUri
}

class cmisTypeDefinitionType {
  public $id; // string
  public $localName; // string
  public $localNamespace; // anyURI
  public $displayName; // string
  public $queryName; // string
  public $description; // string
  public $baseId; // enumBaseObjectTypeIds
  public $parentId; // string
  public $creatable; // boolean
  public $fileable; // boolean
  public $queryable; // boolean
  public $fulltextIndexed; // boolean
  public $includedInSupertypeQuery; // boolean
  public $controllablePolicy; // boolean
  public $controllableACL; // boolean
  public $propertyBooleanDefinition; // cmisPropertyBooleanDefinitionType
  public $propertyDateTimeDefinition; // cmisPropertyDateTimeDefinitionType
  public $propertyDecimalDefinition; // cmisPropertyDecimalDefinitionType
  public $propertyIdDefinition; // cmisPropertyIdDefinitionType
  public $propertyIntegerDefinition; // cmisPropertyIntegerDefinitionType
  public $propertyHtmlDefinition; // cmisPropertyHtmlDefinitionType
  public $propertyStringDefinition; // cmisPropertyStringDefinitionType
  public $propertyUriDefinition; // cmisPropertyUriDefinitionType
}

class cmisTypeDocumentDefinitionType extends cmisTypeDefinitionType {
  public $versionable; // boolean
  public $contentStreamAllowed; // enumContentStreamAllowed
}

class cmisTypeFolderDefinitionType extends cmisTypeDefinitionType {
}

class cmisTypeRelationshipDefinitionType extends cmisTypeDefinitionType {
  public $allowedSourceTypes; // string
  public $allowedTargetTypes; // string
}

class cmisTypePolicyDefinitionType extends cmisTypeDefinitionType {
}

class cmisQueryType {
  public $statement; // string
  public $searchAllVersions; // boolean
  public $includeAllowableActions; // boolean
  public $includeRelationships; // enumIncludeRelationships
  public $renditionFilter; // string
  public $maxItems; // integer
  public $skipCount; // integer
}

class cmisRepositoryInfoType {
  public $repositoryId; // string
  public $repositoryName; // string
  public $repositoryDescription; // string
  public $vendorName; // string
  public $productName; // string
  public $productVersion; // string
  public $rootFolderId; // string
  public $latestChangeLogToken; // string
  public $capabilities; // cmisRepositoryCapabilitiesType
  public $aclCapability; // cmisACLCapabilityType
  public $cmisVersionSupported; // string
  public $thinClientURI; // anyURI
  public $changesIncomplete; // boolean
  public $changesOnType; // enumBaseObjectTypeIds
  public $principalAnonymous; // string
  public $principalAnyone; // string
}

class cmisRepositoryCapabilitiesType {
  public $capabilityACL; // enumCapabilityACL
  public $capabilityAllVersionsSearchable; // boolean
  public $capabilityChanges; // enumCapabilityChanges
  public $capabilityContentStreamUpdatability; // enumCapabilityContentStreamUpdates
  public $capabilityGetDescendants; // boolean
  public $capabilityGetFolderTree; // boolean
  public $capabilityMultifiling; // boolean
  public $capabilityPWCSearchable; // boolean
  public $capabilityPWCUpdatable; // boolean
  public $capabilityQuery; // enumCapabilityQuery
  public $capabilityRenditions; // enumCapabilityRendition
  public $capabilityUnfiling; // boolean
  public $capabilityVersionSpecificFiling; // boolean
  public $capabilityJoin; // enumCapabilityJoin
}

class enumTypeOfChanges {
  const created = 'created';
  const updated = 'updated';
  const deleted = 'deleted';
  const security = 'security';
}

class enumCapabilityChanges {
  const none = 'none';
  const objectidsonly = 'objectidsonly';
  const properties = 'properties';
  const all = 'all';
}

class cmisChangeEventType {
  public $changeType; // enumTypeOfChanges
  public $changeTime; // dateTime
}

class enumACLPropagation {
  const repositorydetermined = 'repositorydetermined';
  const objectonly = 'objectonly';
  const propagate = 'propagate';
}

class enumCapabilityACL {
  const none = 'none';
  const discover = 'discover';
  const manage = 'manage';
}

class enumBasicPermissions {
  const cmis_read = 'cmis:read';
  const cmis_write = 'cmis:write';
  const cmis_all = 'cmis:all';
}

class cmisPermissionDefinition {
  public $permission; // string
  public $description; // string
}

class cmisPermissionMapping {
  public $key; // enumAllowableActionsKey
  public $permission; // string
}

class enumAllowableActionsKey {
  const canGetDescendents_Folder = 'canGetDescendents.Folder';
  const canGetChildren_Folder = 'canGetChildren.Folder';
  const canGetParents_Folder = 'canGetParents.Folder';
  const canGetFolderParent_Object = 'canGetFolderParent.Object';
  const canCreateDocument_Folder = 'canCreateDocument.Folder';
  const canCreateFolder_Folder = 'canCreateFolder.Folder';
  const canCreateRelationship_Source = 'canCreateRelationship.Source';
  const canCreateRelationship_Target = 'canCreateRelationship.Target';
  const canGetProperties_Object = 'canGetProperties.Object';
  const canViewContent_Object = 'canViewContent.Object';
  const canUpdateProperties_Object = 'canUpdateProperties.Object';
  const canMove_Object = 'canMove.Object';
  const canMove_Target = 'canMove.Target';
  const canMove_Source = 'canMove.Source';
  const canDelete_Object = 'canDelete.Object';
  const canDeleteTree_Folder = 'canDeleteTree.Folder';
  const canSetContent_Document = 'canSetContent.Document';
  const canDeleteContent_Document = 'canDeleteContent.Document';
  const canAddToFolder_Object = 'canAddToFolder.Object';
  const canAddToFolder_Folder = 'canAddToFolder.Folder';
  const canRemoveFromFolder_Object = 'canRemoveFromFolder.Object';
  const canRemoveFromFolder_Folder = 'canRemoveFromFolder.Folder';
  const canCheckout_Document = 'canCheckout.Document';
  const canCancelCheckout_Document = 'canCancelCheckout.Document';
  const canCheckin_Document = 'canCheckin.Document';
  const canGetAllVersions_VersionSeries = 'canGetAllVersions.VersionSeries';
  const canGetObjectRelationships_Object = 'canGetObjectRelationships.Object';
  const canAddPolicy_Object = 'canAddPolicy.Object';
  const canAddPolicy_Policy = 'canAddPolicy.Policy';
  const canRemovePolicy_Object = 'canRemovePolicy.Object';
  const canRemovePolicy_Policy = 'canRemovePolicy.Policy';
  const canGetAppliedPolicies_Object = 'canGetAppliedPolicies.Object';
  const canGetACL_Object = 'canGetACL.Object';
  const canApplyACL_Object = 'canApplyACL.Object';
}

class enumUsers {
  const cmis_user = 'cmis:user';
}

class cmisAccessControlPrincipalType {
  public $principalId; // string
}

class cmisAccessControlEntryType {
  public $principal; // cmisAccessControlPrincipalType
  public $permission; // string
  public $direct; // boolean
}

class cmisAccessControlListType {
  public $permission; // cmisAccessControlEntryType
}

class cmisACLCapabilityType {
  public $supportedPermissions; // enumSupportedPermissions
  public $propagation; // enumACLPropagation
  public $permissions; // cmisPermissionDefinition
  public $mapping; // cmisPermissionMapping
}

class enumSupportedPermissions {
  const basic = 'basic';
  const repository = 'repository';
  const both = 'both';
}

class enumCapabilityRendition {
  const none = 'none';
  const read = 'read';
}

class enumRenditionKind {
  const cmis_thumbnail = 'cmis:thumbnail';
}

class cmisRenditionType {
  public $streamId; // string
  public $mimetype; // string
  public $length; // integer
  public $kind; // string
  public $title; // string
  public $height; // integer
  public $width; // integer
  public $renditionDocumentId; // string
}

class cmisFaultType {
  public $type; // enumServiceException
  public $code; // integer
  public $message; // string
}

class enumServiceException {
  const constraint = 'constraint';
  const nameConstraintViolation = 'nameConstraintViolation';
  const contentAlreadyExists = 'contentAlreadyExists';
  const filterNotValid = 'filterNotValid';
  const invalidArgument = 'invalidArgument';
  const notSupported = 'notSupported';
  const objectNotFound = 'objectNotFound';
  const permissionDenied = 'permissionDenied';
  const runtime = 'runtime';
  const storage = 'storage';
  const streamNotSupported = 'streamNotSupported';
  const updateConflict = 'updateConflict';
  const versioning = 'versioning';
}

class cmisExtensionType {
}

class cmisTypeContainer {
  public $type; // cmisTypeDefinitionType
  public $children; // cmisTypeContainer
}

class cmisTypeDefinitionListType {
  public $types; // cmisTypeDefinitionType
  public $hasMoreItems; // boolean
  public $numItems; // integer
}

class cmisObjectInFolderContainerType {
  public $objectInFolder; // cmisObjectInFolderType
  public $children; // cmisObjectInFolderContainerType
}

class cmisObjectListType {
  public $objects; // cmisObjectType
  public $hasMoreItems; // boolean
  public $numItems; // integer
}

class cmisObjectInFolderType {
  public $object; // cmisObjectType
  public $pathSegment; // string
}

class cmisObjectParentsType {
  public $object; // cmisObjectType
  public $relativePathSegment; // string
}

class cmisObjectInFolderListType {
  public $objects; // cmisObjectInFolderType
  public $hasMoreItems; // boolean
  public $numItems; // integer
}

class cmisRepositoryEntryType {
  public $repositoryId; // string
  public $repositoryName; // string
}

class cmisContentStreamType {
  public $length; // integer
  public $mimeType; // string
  public $filename; // string
  public $stream; // base64Binary
}

class cmisACLType {
  public $ACL; // cmisAccessControlListType
  public $exact; // boolean
}

class getRepositories {
  public $extension; // cmisExtensionType
}

class getRepositoriesResponse {
  public $repositories; // cmisRepositoryEntryType
}

class getRepositoryInfo {
  public $repositoryId; // string
  public $extension; // cmisExtensionType
}

class getRepositoryInfoResponse {
  public $repositoryInfo; // cmisRepositoryInfoType
}

class getTypeChildren {
  public $repositoryId; // string
  public $typeId; // string
  public $includePropertyDefinitions; // boolean
  public $maxItems; // integer
  public $skipCount; // integer
  public $extension; // cmisExtensionType
}

class getTypeChildrenResponse {
  public $types; // cmisTypeDefinitionListType
}

class getTypeDescendants {
  public $repositoryId; // string
  public $typeId; // string
  public $depth; // integer
  public $includePropertyDefinitions; // boolean
  public $extension; // cmisExtensionType
}

class getTypeDescendantsResponse {
  public $types; // cmisTypeContainer
}

class getTypeDefinition {
  public $repositoryId; // string
  public $typeId; // string
  public $extension; // cmisExtensionType
}

class getTypeDefinitionResponse {
  public $type; // cmisTypeDefinitionType
}

class getDescendants {
  public $repositoryId; // string
  public $folderId; // string
  public $depth; // integer
  public $filter; // string
  public $includeAllowableActions; // boolean
  public $includeRelationships; // enumIncludeRelationships
  public $renditionFilter; // string
  public $includePathSegment; // boolean
  public $extension; // cmisExtensionType
}

class getDescendantsResponse {
  public $objects; // cmisObjectInFolderContainerType
}

class getFolderTree {
  public $repositoryId; // string
  public $folderId; // string
  public $depth; // integer
  public $filter; // string
  public $includeAllowableActions; // boolean
  public $includeRelationships; // enumIncludeRelationships
  public $renditionFilter; // string
  public $includePathSegment; // boolean
  public $extension; // cmisExtensionType
}

class getFolderTreeResponse {
  public $objects; // cmisObjectInFolderContainerType
}

class getChildren {
  public $repositoryId; // string
  public $folderId; // string
  public $filter; // string
  public $orderBy; // string
  public $includeAllowableActions; // boolean
  public $includeRelationships; // enumIncludeRelationships
  public $renditionFilter; // string
  public $includePathSegment; // boolean
  public $maxItems; // integer
  public $skipCount; // integer
  public $extension; // cmisExtensionType
}

class getChildrenResponse {
  public $objects; // cmisObjectInFolderListType
}

class getFolderParent {
  public $repositoryId; // string
  public $folderId; // string
  public $filter; // string
  public $extension; // cmisExtensionType
}

class getFolderParentResponse {
  public $object; // cmisObjectType
}

class getObjectParents {
  public $repositoryId; // string
  public $objectId; // string
  public $filter; // string
  public $includeAllowableActions; // boolean
  public $includeRelationships; // enumIncludeRelationships
  public $renditionFilter; // string
  public $includeRelativePathSegment; // boolean
  public $extension; // cmisExtensionType
}

class getObjectParentsResponse {
  public $parents; // cmisObjectParentsType
}

class getRenditions {
  public $repositoryId; // string
  public $objectId; // string
  public $renditionFilter; // string
  public $maxItems; // integer
  public $skipCount; // integer
  public $extension; // cmisExtensionType
}

class getRenditionsResponse {
  public $renditions; // cmisRenditionType
}

class getCheckedOutDocs {
  public $repositoryId; // string
  public $folderId; // string
  public $filter; // string
  public $orderBy; // string
  public $includeAllowableActions; // boolean
  public $includeRelationships; // enumIncludeRelationships
  public $renditionFilter; // string
  public $maxItems; // integer
  public $skipCount; // integer
  public $extension; // cmisExtensionType
}

class getCheckedOutDocsResponse {
  public $objects; // cmisObjectListType
}

class createDocument {
  public $repositoryId; // string
  public $properties; // cmisPropertiesType
  public $folderId; // string
  public $contentStream; // cmisContentStreamType
  public $versioningState; // enumVersioningState
  public $policies; // string
  public $addACEs; // cmisAccessControlListType
  public $removeACEs; // cmisAccessControlListType
  public $extension; // cmisExtensionType
}

class createDocumentResponse {
  public $objectId; // string
  public $extension; // cmisExtensionType
}

class createDocumentFromSource {
  public $repositoryId; // string
  public $sourceId; // string
  public $properties; // cmisPropertiesType
  public $folderId; // string
  public $versioningState; // enumVersioningState
  public $policies; // string
  public $addACEs; // cmisAccessControlListType
  public $removeACEs; // cmisAccessControlListType
  public $extension; // cmisExtensionType
}

class createDocumentFromSourceResponse {
  public $objectId; // string
  public $extension; // cmisExtensionType
}

class createFolder {
  public $repositoryId; // string
  public $properties; // cmisPropertiesType
  public $folderId; // string
  public $policies; // string
  public $addACEs; // cmisAccessControlListType
  public $removeACEs; // cmisAccessControlListType
  public $extension; // cmisExtensionType
}

class createFolderResponse {
  public $objectId; // string
  public $extension; // cmisExtensionType
}

class createRelationship {
  public $repositoryId; // string
  public $properties; // cmisPropertiesType
  public $policies; // string
  public $addACEs; // cmisAccessControlListType
  public $removeACEs; // cmisAccessControlListType
  public $extension; // cmisExtensionType
}

class createRelationshipResponse {
  public $objectId; // string
  public $extension; // cmisExtensionType
}

class createPolicy {
  public $repositoryId; // string
  public $properties; // cmisPropertiesType
  public $folderId; // string
  public $policies; // string
  public $addACEs; // cmisAccessControlListType
  public $removeACEs; // cmisAccessControlListType
  public $extension; // cmisExtensionType
}

class createPolicyResponse {
  public $objectId; // string
  public $extension; // cmisExtensionType
}

class getAllowableActions {
  public $repositoryId; // string
  public $objectId; // string
  public $extension; // cmisExtensionType
}

class getAllowableActionsResponse {
  public $allowableActions; // cmisAllowableActionsType
}

class getProperties {
  public $repositoryId; // string
  public $objectId; // string
  public $filter; // string
  public $extension; // cmisExtensionType
}

class getPropertiesResponse {
  public $properties; // cmisPropertiesType
}

class getObject {
  public $repositoryId; // string
  public $objectId; // string
  public $filter; // string
  public $includeAllowableActions; // boolean
  public $includeRelationships; // enumIncludeRelationships
  public $renditionFilter; // string
  public $includePolicyIds; // boolean
  public $includeACL; // boolean
  public $extension; // cmisExtensionType
}

class getObjectResponse {
  public $object; // cmisObjectType
}

class getObjectByPath {
  public $repositoryId; // string
  public $path; // string
  public $filter; // string
  public $includeAllowableActions; // boolean
  public $includeRelationships; // enumIncludeRelationships
  public $renditionFilter; // string
  public $includePolicyIds; // boolean
  public $includeACL; // boolean
  public $extension; // cmisExtensionType
}

class getObjectByPathResponse {
  public $object; // cmisObjectType
}

class getContentStream {
  public $repositoryId; // string
  public $objectId; // string
  public $streamId; // string
  public $offset; // integer
  public $length; // integer
  public $extension; // cmisExtensionType
}

class getContentStreamResponse {
  public $contentStream; // cmisContentStreamType
}

class updateProperties {
  public $repositoryId; // string
  public $objectId; // string
  public $changeToken; // string
  public $properties; // cmisPropertiesType
  public $extension; // cmisExtensionType
}

class updatePropertiesResponse {
  public $objectId; // string
  public $changeToken; // string
  public $extension; // cmisExtensionType
}

class moveObject {
  public $repositoryId; // string
  public $objectId; // string
  public $targetFolderId; // string
  public $sourceFolderId; // string
  public $extension; // cmisExtensionType
}

class moveObjectResponse {
  public $objectId; // string
  public $extension; // cmisExtensionType
}

class deleteObject {
  public $repositoryId; // string
  public $objectId; // string
  public $allVersions; // boolean
  public $extension; // cmisExtensionType
}

class deleteObjectResponse {
  public $extension; // cmisExtensionType
}

class deleteTree {
  public $repositoryId; // string
  public $folderId; // string
  public $allVersions; // boolean
  public $unfileObjects; // enumUnfileObject
  public $continueOnFailure; // boolean
  public $extension; // cmisExtensionType
}

class deleteTreeResponse {
  public $failedToDelete; // failedToDelete
}

class failedToDelete {
  public $objectIds; // string
}

class setContentStream {
  public $repositoryId; // string
  public $objectId; // string
  public $overwriteFlag; // boolean
  public $changeToken; // string
  public $contentStream; // cmisContentStreamType
  public $extension; // cmisExtensionType
}

class setContentStreamResponse {
  public $objectId; // string
  public $changeToken; // string
  public $extension; // cmisExtensionType
}

class deleteContentStream {
  public $repositoryId; // string
  public $objectId; // string
  public $changeToken; // string
  public $extension; // cmisExtensionType
}

class deleteContentStreamResponse {
  public $objectId; // string
  public $changeToken; // string
  public $extension; // cmisExtensionType
}

class addObjectToFolder {
  public $repositoryId; // string
  public $objectId; // string
  public $folderId; // string
  public $allVersions; // boolean
  public $extension; // cmisExtensionType
}

class addObjectToFolderResponse {
  public $extension; // cmisExtensionType
}

class removeObjectFromFolder {
  public $repositoryId; // string
  public $objectId; // string
  public $folderId; // string
  public $extension; // cmisExtensionType
}

class removeObjectFromFolderResponse {
  public $extension; // cmisExtensionType
}

class query {
  public $repositoryId; // string
  public $statement; // string
  public $searchAllVersions; // boolean
  public $includeAllowableActions; // boolean
  public $includeRelationships; // enumIncludeRelationships
  public $renditionFilter; // string
  public $maxItems; // integer
  public $skipCount; // integer
  public $extension; // cmisExtensionType
}

class queryResponse {
  public $objects; // cmisObjectListType
}

class getContentChanges {
  public $repositoryId; // string
  public $changeLogToken; // string
  public $includeProperties; // boolean
  public $filter; // string
  public $includePolicyIds; // boolean
  public $includeACL; // boolean
  public $maxItems; // integer
  public $extension; // cmisExtensionType
}

class getContentChangesResponse {
  public $objects; // cmisObjectListType
  public $changeLogToken; // string
}

class checkOut {
  public $repositoryId; // string
  public $objectId; // string
  public $extension; // cmisExtensionType
}

class checkOutResponse {
  public $objectId; // string
  public $contentCopied; // boolean
  public $extension; // cmisExtensionType
}

class cancelCheckOut {
  public $repositoryId; // string
  public $objectId; // string
  public $extension; // cmisExtensionType
}

class cancelCheckOutResponse {
  public $extension; // cmisExtensionType
}

class checkIn {
  public $repositoryId; // string
  public $objectId; // string
  public $major; // boolean
  public $properties; // cmisPropertiesType
  public $contentStream; // cmisContentStreamType
  public $checkinComment; // string
  public $policies; // string
  public $addACEs; // cmisAccessControlListType
  public $removeACEs; // cmisAccessControlListType
  public $extension; // cmisExtensionType
}

class checkInResponse {
  public $objectId; // string
  public $extension; // cmisExtensionType
}

class getPropertiesOfLatestVersion {
  public $repositoryId; // string
  public $objectId; // string
  public $major; // boolean
  public $filter; // string
  public $extension; // cmisExtensionType
}

class getPropertiesOfLatestVersionResponse {
  public $properties; // cmisPropertiesType
}

class getObjectOfLatestVersion {
  public $repositoryId; // string
  public $objectId; // string
  public $major; // boolean
  public $filter; // string
  public $includeAllowableActions; // boolean
  public $includeRelationships; // enumIncludeRelationships
  public $renditionFilter; // string
  public $includePolicyIds; // boolean
  public $includeACL; // boolean
  public $extension; // cmisExtensionType
}

class getObjectOfLatestVersionResponse {
  public $object; // cmisObjectType
}

class getAllVersions {
  public $repositoryId; // string
  public $objectId; // string
  public $filter; // string
  public $includeAllowableActions; // boolean
  public $extension; // cmisExtensionType
}

class getAllVersionsResponse {
  public $objects; // cmisObjectType
}

class getObjectRelationships {
  public $repositoryId; // string
  public $objectId; // string
  public $includeSubRelationshipTypes; // boolean
  public $relationshipDirection; // enumRelationshipDirection
  public $typeId; // string
  public $filter; // string
  public $includeAllowableActions; // boolean
  public $maxItems; // integer
  public $skipCount; // integer
  public $extension; // cmisExtensionType
}

class getObjectRelationshipsResponse {
  public $objects; // cmisObjectListType
}

class applyPolicy {
  public $repositoryId; // string
  public $policyId; // string
  public $objectId; // string
  public $extension; // cmisExtensionType
}

class applyPolicyResponse {
  public $extension; // cmisExtensionType
}

class removePolicy {
  public $repositoryId; // string
  public $policyId; // string
  public $objectId; // string
  public $extension; // cmisExtensionType
}

class removePolicyResponse {
  public $extension; // cmisExtensionType
}

class getAppliedPolicies {
  public $repositoryId; // string
  public $objectId; // string
  public $filter; // string
  public $extension; // cmisExtensionType
}

class getAppliedPoliciesResponse {
  public $objects; // cmisObjectType
}

class getACL {
  public $repositoryId; // string
  public $objectId; // string
  public $onlyBasicPermissions; // boolean
  public $extension; // cmisExtensionType
}

class getACLResponse {
  public $ACL; // cmisACLType
}

class applyACL {
  public $repositoryId; // string
  public $objectId; // string
  public $addACEs; // cmisAccessControlListType
  public $removeACEs; // cmisAccessControlListType
  public $ACLPropagation; // enumACLPropagation
  public $extension; // cmisExtensionType
}

class applyACLResponse {
  public $ACL; // cmisACLType
}

class anyURI {
}

?>
