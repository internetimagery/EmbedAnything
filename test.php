<?php
// File used for testing. Should run through and check things are working.
require_once(__DIR__."/vendor/vendor/autoload.php");

// Embed functionality.
require_once('includes/data.php');

// Test request
var_dump(EA_Request('http://internetimagery.com'));

// Test maintenance
//EA_CacheMaintenance();

// Test embed
//var_dump(EA_Embed('http://internetimagery.com'));