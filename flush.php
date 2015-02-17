<?php
// Flush the Cache. Do this if it gets unwieldy or if updating the extension.
require_once(__DIR__."/vendor/autoload.php");
require_once('includes/data.php');

EA_EmptyCache();
echo "CACHE FLUSHED! :)";
