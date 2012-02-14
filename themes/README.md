Lydia, the theme directory
=========================

Here is the themes. A theme is a collection of PHP-code, CSS and HTML that creates the resulting 
webpage. A minimal theme shall consist of three files.

* `style.css`, here is a regular stylesheet.
* `functions.php`, template functions, loaded just before the template-file.
* `default.tlp.php` is the default template file with HTML combined with a template language.

The contents of the `$ly->data[]` is made available to the theme.

The file `theme/functions.php` contains theme-helpers that is most likely useful in all themes.