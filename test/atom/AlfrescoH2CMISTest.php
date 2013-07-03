<?php
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
	public function testGetSites() {
		$folder = $this->client->getObjectByPath("/Sites");
		$this->assertEquals("F:st:sites",$folder->properties["cmis:objectTypeId"]);
		$this->assertEquals("cmis:folder",$folder->properties["cmis:baseTypeId"]);
	}
}
?>
