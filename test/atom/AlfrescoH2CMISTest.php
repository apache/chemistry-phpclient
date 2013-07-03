<?php
/*

This set of tests are designed to run against an clean (new) Alfresco repository
The easiest way to run this would be to have a repository that uses an H2 database (for easy reseting)

*/
require_once('../utils/phpunit.phar');
require_once('../../atom/cmis-lib.php');
class AlfrescoCMISH2Test extends PHPUnit_Framework_TestCase
{
	protected $client;
	protected function setUp() {
		$repo_url = "http://localhost:8080/alfresco/cmisatom";
		$repo_username = "admin";
		$repo_password = "admin";
		$this->client = new CMISService($repo_url, $repo_username, $repo_password);
	}
	public function testGetFolder() {
		/*
		 * This test gets an known folder and tests the ability to retreive know properties
		 */
		$folder = $this->client->getObjectByPath("/Sites");
		$this->assertEquals("F:st:sites",$folder->properties["cmis:objectTypeId"]);
		$this->assertEquals("cmis:folder",$folder->properties["cmis:baseTypeId"]);
	}
	/**
	 * @expectedException CmisObjectNotFoundException
	 */
	public function testInvalidCreateFolder() {
		$folder = $this->client->getObjectByPath("/x");
		$folder = $this->client->createFolder($folder->id,"TEST");
	}
}
?>
