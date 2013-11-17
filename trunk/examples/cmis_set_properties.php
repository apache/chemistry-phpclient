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

require_once ('cmis_repository_wrapper.php');
$repo_url = $_SERVER["argv"][1];
$repo_username = $_SERVER["argv"][2];
$repo_password = $_SERVER["argv"][3];
$repo_object_id = $_SERVER["argv"][4];
$repo_property_id = $_SERVER["argv"][5];
$repo_property_values = $_SERVER["argv"][6];
$repo_debug = 3;

$client = new CMISService($repo_url, $repo_username, $repo_password);

if ($repo_debug)
{
    print "Repository Information:\n===========================================\n";
    print_r($client->workspace);
    print "\n===========================================\n\n";
}

$myobject = $client->getObject($repo_object_id);
if ($repo_debug)
{
    print "My Object:\n===========================================\n";
    print_r($myobject);
    print "\n===========================================\n\n";
}
$mypropmap = array( $repo_property_id => explode(",",$repo_property_values));
$myobject = $client->updateProperties($myobject->id,$mypropmap);
if ($repo_debug)
{
    print "Updated Object\n:\n===========================================\n";
    print_r($myobject);
    print "\n===========================================\n\n";
}

if ($repo_debug > 2)
{
    print "Final State of CLient:\n===========================================\n";
    print_r($client);
}
