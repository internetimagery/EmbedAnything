# EmbedAnything
Mediawiki extension to embed media into a page

* Use with:
```
<embed>URL</embed>
```
OR
```
<embed url=URL></embed>
```
OR
```
{{#tag:embed|URL}}
```
* Use "template" or "style" attribute to select template to use for embedding. ie:
```
<embed url=URL template=TEMPLATE></embed>
```
* Other attributes get passed on to template and will do various things, defined in the template. ie:
```
<embed url=URL template=TEMPLATE width=WIDTH></embed>
```
The above will send "width" to the template, and if it is supported, will adjust the width of the embed.

## Templates

Templates are placed in
```
../Extensions/EmbedAnything/includes/template
```

Templates must be PHP files and must output their content. You get access to two variables in your template:
$data and $options.
* $data contains all the data for the embed. Things such as images, text etc.
* $options contains the options passed on to the template. Whatever the person put into the embed tag. Use this for altering visuals of the template. ie: change the width, the colour, turn on or off elements.

## Configuration

There are a few conguration options thrown around the include files:

###data.php

```php
define('EA_SOUNDCLOUD_KEY',null); // Soundcloud key
define('EA_FACEBOOK_KEY',null); // Facebook key
define('EA_EMBEDLY_KEY',null); // Embedly key

// Default Images and Icons if missing
$image_directory = 'extensions/EmbedAnything/includes';
define('EA_DEFAULT_IMG'		, "$image_directory/missing_image.png");
define('EA_DEFAULT_ICON' 	, "$image_directory/missing_icon.png");

// Cache file
define('EA_CACHE_DIR',__DIR__.'/cache'); // Cache directory
define('EA_CACHE_TIME', 1 * 60 * 60 * 24 * 7); // Cache expiry time. Items older than this will be regenerated.
```

###formHtml.php

```php
// Set the default template. The file name without php
define('EA_DEFAULT_TEMPLATE', 'SimpleBox');
define('EA_TEMPLATE_DIR', __DIR__.'/template');
```