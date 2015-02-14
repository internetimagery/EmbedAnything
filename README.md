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