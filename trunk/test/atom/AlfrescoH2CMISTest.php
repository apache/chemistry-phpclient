<?php
/*

This set of tests are designed to run against an clean (new) Alfresco repository
The easiest way to run this would be to have a repository that uses an H2 database (for easy reseting)

*/
require_once('../utils/phpunit.phar');
require_once('../../atom/cmis-lib.php');
class AlfrescoCMISH2Test extends PHPUnit_Framework_TestCase
{
	protected static $client;
	protected static $unitTestFolder;
	public static function setUpBeforeClass() {
		echo "Set Up Before Class";
		$repo_url = "http://localhost:8080/alfresco/cmisatom";
		$repo_username = "admin";
		$repo_password = "admin";
		AlfrescoCMISH2Test::$client = new CMISService($repo_url, $repo_username, $repo_password);
		$rootFolder = AlfrescoCMISH2Test::$client->getObjectByPath("/");
		$unitTestFolderHome = null;
		try {
			$unitTestFolderHome = AlfrescoCMISH2Test::$client->getObjectByPath("/UnitTest");
		} catch (CmisObjectNotFoundException $x) {
			$unitTestFolderHome = AlfrescoCMISH2Test::$client->createFolder($rootFolder->id,"UnitTest");
		}
		AlfrescoCMISH2Test::$unitTestFolder = AlfrescoCMISH2Test::$client->createFolder($unitTestFolderHome->id,base_convert(time(),10,36));
	}
	protected function setUp() {
	}
	public function testGetFolder() {
		/*
		 * This test gets an known folder and tests the ability to retreive know properties
		 */
		$folder = AlfrescoCMISH2Test::$client->getObjectByPath("/Sites");
		$this->assertEquals("F:st:sites",$folder->properties["cmis:objectTypeId"]);
		$this->assertEquals("cmis:folder",$folder->properties["cmis:baseTypeId"]);
	}
	public function testGetFolderParent() {
		/*
		 * This test gets an known folder and tests the ability to retreive know properties
		 */
		$folder = AlfrescoCMISH2Test::$client->getObjectByPath("/Sites");
		$parentFolder = AlfrescoCMISH2Test::$client->getFolderParent($folder->id);
		$rootFolder = AlfrescoCMISH2Test::$client->getObjectByPath("/");
		$this->assertEquals($parentFolder->id,$rootFolder->id);
	}
	/**
	 * @expectedException CmisObjectNotFoundException
	 */
	public function testInvalidCreateFolder() {
		$folder = AlfrescoCMISH2Test::$client->getObjectByPath("/x");
		$folder = AlfrescoCMISH2Test::$client->createFolder($folder->id,"TEST");
	}
	
	/**
	 * Create a Folder and change its name
	 * This will only work in an Alfresco Repository
	 */	
	 public function testRenameFolder() {
		$folder = AlfrescoCMISH2Test::$client->createFolder(AlfrescoCMISH2Test::$unitTestFolder->id,"TEST",array("cmis:objectTypeId" => "F:cmiscustom:folder","cmiscustom:folderprop_string" => "Original Value"));
		$newProps = array( "cmis:name" => "Renamed Test","cmiscustom:folderprop_string" => "New Value");
		$this->assertEquals("Original Value",$folder->properties["cmiscustom:folderprop_string"]);
		$folder = AlfrescoCMISH2Test::$client->updateProperties($folder->id,$newProps);
		$this->assertEquals("Renamed Test",$folder->properties["cmis:name"]);
		$this->assertEquals("New Value",$folder->properties["cmiscustom:folderprop_string"]);
	}
	 
}