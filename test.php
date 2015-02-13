<?php
// File used for testing. Should run through and check things are working.
require_once(__DIR__."/vendor/vendor/autoload.php");

// Embed functionality.
require_once('includes/embed.php');

// Test request
var_dump(EA_Request('http://internetimagery.com'));