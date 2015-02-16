<?php
// File used for testing. Should run through and check things are working.
require_once(__DIR__."/vendor/autoload.php");

// Embed functionality.
require_once('includes/data.php');
require_once("includes/formHTML.php");

// Flush Cache
EA_EmptyCache();

// Test request
//var_dump(EA_Request('http://internetimagery.com'));

// Test maintenance
//EA_CacheMaintenance();

// Test embed
//var_dump(EA_Embed('http://internetimagery.com'));

// Test Readability
//$data = EA_Embed('http://internetimagery.com');
//print_r($data['content']);

// Test code
//echo EA_SimpleBox('http://www.youtube.com/watch?v=_IQuDbycE3k');
//echo EA_SimpleBox('http://imgur.com/gallery/VacL6uP');
//echo EA_SimpleBox('http://soundcloud.com/horse_enthusiast/shine-box');
//echo EA_SimpleBox('http://www.cartoonbrew.com/interactive/oculus-launches-story-studio-to-explore-vr-cinema-possibilities-109160.html');
//echo EA_SimpleBox('https://vimeo.com/114654358');
//echo EA_SimpleBox('http://yourlisten.com/electronicmusic/turbo-suit-coogi-wolf');
//echo EA_SimpleBox('http://www.jango.com/stations/263448187/tunein?gcid=1&l=0');
//echo EA_SimpleBox('http://isbndb.com/book/atlas_of_human_anatomy_for_the_artist');
//echo EA_SimpleBox('http://www.amazon.co.uk/Institute-Actuaries-Principles-Annuities-Assurances/dp/1178603857/ref=sr_1_1?ie=UTF8&qid=1423843292&sr=8-1&keywords=iof+life');