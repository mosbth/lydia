Lydia, the site/src directory
=========================

The directory `site/src` contains classes for addon controllers, models and more. The autoloader 
looks here first for a class file, before it moves on to look in `LYDIA_INSTALL_PATH/src`. It is 
therefore possible to replace core-classes with your own version, just use same classname and 
place it here.