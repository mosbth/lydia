Lydia, a PHP-based, MVC-inspired CMF
====================================

This project is used while teaching advanced PHP-programming with Model View Controller (MVC)
frameworks with a taste of Content Management Framework (CMF). 

Material below is mainly in swedish.

* http://dbwebb.se/lydia (try out the code)
* http://dbwebb.se/f/123 (forum with some tutorials on how the code was built, only in swedish)


History
-------

Todo.

* Yes, a lot of things to do.

v0.1.3 (2012-02-15) 

* Added some style and an icon the the core theme.
* Added a guestbook application as an example of a controller using database and forms.
* The guestbook is fully working and stores entries in the session.
* Cope with querystring sent to controller and methods.
* Added theme helper theme_url($url) which prepends $url with the url to the theme directory.
* Added session_start() in CLydia.
* Use default timezone as defined in site/config.php, set in CLydia::__construct()


v0.1.2 (2012-02-15) 

* One common baseclass for controllers and modules, CObject, holds access to CLydia through $this->
* Output from theme helper get_debug() is configurable in site/config.php.
* Wrapper for htmlent() for htmlentities() to support character encoding from site/config.php.
* Remove all - and _ from the url before checking if method exists. Enables developer/display-object 
and developer/display_object to point to developer/displayobject.


v0.1.1 (2012-02-14) 
v0.1.0 (2012-02-14) 

* All requests handled by `index.php` and using mod_rewrite in `.htaccess`. 
* A base structure with `bootstrap.php`, frontcontroller and theme engine.
* Frontcontroller `CLydia::FronControllerRoute()` supporting varius url-constructs.
* A basic theme controller, `CLydia::ThemeEngineRender()`, with `functions.php`, `style.css` and template files.
* Managing base_url and introducing theme helper functions.
* 'CRequest' manages creation of internal links.

v0.01 - v0.03 (2011 december)
* This was the first release used in a course called dbwebb2.


 .   
..:  Copyright 2011 by Mikael Roos (me@mikaelroos.se)
