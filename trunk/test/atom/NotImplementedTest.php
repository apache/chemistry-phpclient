<?php
/*

This set of tests are designed to run against an clean (new) Alfresco repository
The easiest way to run this would be to have a repository that uses an H2 database (for easy reseting)

*/
require_once('../utils/phpunit.phar');
require_once('../../atom/cmis-lib.php');
class NotImplementedTest extends PHPUnit_Framework_TestCase
{
	protected $client;
	protected function setUp() {
		$repo_url = "http://localhost:8080/alfresco/cmisatom";
		$repo_username = "admin";
		$repo_password = "admin";
		$this->client = new CMISService($repo_url, $repo_username, $repo_password);
	}
	/**
	 * @expectedException CmisNotImplementedException
	 * @expectedExceptionMessage getRepositories
	 */
	public function testGetRepositories() {
		$this->client->getRepositories();
	}
	
	/**
	 * @expectedException CmisNotImplementedException
	 * @expectedExceptionMessage createType
	 */
	public function testCreateType() {
		$this->client->createType("");
	}

	/**
	 * @expectedException CmisNotImplementedException
	 * @expectedExceptionMessage updateType
	 */
	public function testUpdateType() {
		$this->client->updateType("");
	}
	/**
	 * @expectedException CmisNotImplementedException
	 * @expectedExceptionMessage deleteType
	 */
	public function testDeleteType() {
		$this->client->deleteType("");
	}
	/**
	 * @expectedException CmisNotImplementedException
	 * @expectedExceptionMessage createRelationship
	 */
	public function testCreateRelationship() {
		$this->client->createRelationship();
	}

	/**
	 * @expectedException CmisNotImplementedException
	 * @expectedExceptionMessage createPolicy
	 */
	public function testCreatePolicy() {
		$this->client->createPolicy();
	}
	/**
	 * @expectedException CmisNotImplementedException
	 * @expectedExceptionMessage createItem
	 */
	public function testCreateItem() {
		$this->client->createItem();
	}
	/**
	 * @expectedException CmisNotImplementedException
	 * @expectedExceptionMessage bulkUpdateProperties
	 */
	public function testBulkUpdateProperties() {
		$this->client->bulkUpdateProperties();
	}
	
	/**
	 * @expectedException CmisNotImplementedException
	 * @expectedExceptionMessage appendContentStream
	 */
	public function testAppendContentStream() {
		$this->client->appendContentStream("","","","");
	}

	/**
	 * @expectedException CmisNotImplementedException
	 * @expectedExceptionMessage getAllVersions
	 */
	public function testGetAllVersions() {
		$this->client->getAllVersions("");
	}
	/**
	 * @expectedException CmisNotImplementedException
	 * @expectedExceptionMessage deleteAllVersions
	 */
	public function testDeleteAllVersions() {
		$this->client->deleteAllVersions("");
	}
	/**
	 * @expectedException CmisNotImplementedException
	 * @expectedExceptionMessage getObjectRelationships
	 */
	public function testGetObjectRelationships() {
		$this->client->getObjectRelationships();
	}

	/**
	 * @expectedException CmisNotImplementedException
	 * @expectedExceptionMessage getAppliedPolicies
	 */
	public function testGetAppliedPolicies() {
		$this->client->getAppliedPolicies();
	}
	/**
	 * @expectedException CmisNotImplementedException
	 * @expectedExceptionMessage applyPolicy
	 */
	public function testApplyPolicy() {
		$this->client->applyPolicy();
	}
	/**
	 * @expectedException CmisNotImplementedException
	 * @expectedExceptionMessage removePolicy
	 */
	public function testRemovePolicy() {
		$this->client->removePolicy();
	}

	/**
	 * @expectedException CmisNotImplementedException
	 * @expectedExceptionMessage getACL
	 */
	public function testGetACL() {
		$this->client->getACL();
	}
	/**
	 * @expectedException CmisNotImplementedException
	 * @expectedExceptionMessage applyACL
	 */
	public function testApplyACL() {
		$this->client->applyACL();
	}

}