Lydia, a PHP-based, MVC-inspired CMF
====================================

This project is used while teaching advanced PHP-programming with Model View Controller (MVC)
frameworks with a taste of Content Management Framework (CMF). 

Material in below links are mainly in swedish.

* http://dbwebb.se/lydia/current (try out the code)
* http://dbwebb.se/f/123 (forum with some tutorials on how the code was built, only in swedish)


License
-------

Lydia is licensed according to MIT-license. Will consider double licensing with GPL in the future.
Any included external modules are subject to their own licensing.


Use of external libraries
-----------------------------------

Lydia uses external libraries for state of the art samples. Any external module can be replaced or
removed for less features but without disturbing the Lydia core functionality.

The following external modules are included in Lydia.

### HTMLPurifier
HTMLPurifier by Edward Z. Yang to filter & format HTML.
* Website: http://htmlpurifier.org/ 
* Version: 4.4.0 (2012-01-18)
* License: LGPL
* Lydia path: `src/CTextFilter/htmlpurifier-4.4.0-standalone`
* Used by: `CTextFilter`


### PHP Markdown & PHP Markdown Extra
PHP Markdown by Michel Fortin to filter text to HTML to write for the web. Based on the concept of Markdown by John Gruber.
* Website: PHP markdown: http://michelf.com/projects/php-markdown/
* Website: Markdown: http://daringfireball.net/projects/markdown/
* Version: PHP Markdown: 1.0.1o (2012-01-08)
* Version: PHP Markdown Extra 1.2.5 (2012-01-08)
* License: PHP Markdown & PHP Markdown Extra has BSD-style open source license OR GNU General Public License version 2 or a later version.
* License: Markdown has BSD-style open source license.
* Lydia path: `src/CTextFilter/php-markdown`
* Used by: `CTextFilter`


### PHP SmartyPants & PHP Typographer
PHP SmartyPants and PHP Typographer by Michel Fortin for better typography. Based on the concept of Markdown by John Gruber.
* Website: PHP SmartyPants: http://michelf.com/projects/php-smartypants/
* Website: PHP Typographer: http://michelf.com/projects/php-smartypants/typographer/
* Website: SmartyPants: http://daringfireball.net/projects/smartypants/
* Version: PHP SmartyPants: 1.5.1e (2005-12-09)
* Version: PHP Typographer: 1.0 (2006-06-28)
* License: PHP SmartyPants & PHP Typographer has BSD-style open source license.
* License: SmartyPants has BSD-style open source license.
* Lydia path: `src/CTextFilter/php_smartypants_1.5.1e`
* Lydia path: `src/CTextFilter/php_smartypants_typographer_1.0`
* Used by: `CTextFilter`


### SimplePie
SimplePie by Geoffrey, Ryan P., and Ryan M. to read RSS feeds.
* Website: http://simplepie.org/
* Version: 1.3.1 (2012-10-30)
* License: BSD license
* Lydia path: `src/CRSSFeed/simplepie`
* Used by: `CRSSFeed`


### lessphp
lessphp by leaf to compile LESS.
* Website: http://leafo.net/lessphp
* Version: 0.3.8 (2012-08-18)
* License: Dual license, MIT LICENSE and GPL VERSION 3
* Lydia path: `themes/grid/lessphp`
* Used by: `themes/grid/style.php`


### The Semantic Grid System
by Tyler Tate/TwigKit to get grid layout through LESS.
* Website: http://semantic.gs/
* Version: 1.2 (2012-01-11)
* License: Apache License
* Lydia path: `themes/grid/semantic.gs`
* Used by: `themes/grid/style.less`, `themes/base/style/semanticgs.less`


### GeSHi - Generic Syntax Highlighter
by Nigel McNie, Benny Baumann.
* Website: http://qbnz.com/highlighter/
* Version: 1.0.8.10 (2011-02-11)
* License: GNU GPL v2
* Lydia path: `src/CTextFilter/geshi_1.0.8.10`
* Used by: `CTextFilter`


Todo.
-----

Yes, a lot of things to do. Lydia is not very stable and this list is not nere complete,
its more a memory management and wishlist for me when developing.

* Create view-directory and perform alternative load between view and site/view.
* Add tabs to module-manager and enable management of each module.
* Add source.php and enable viewing sourceode of modules.
* Add tab to module-manager to enable translation of each module, with connection to 
external translation service, such as google translate.



History
-------

v0.3.9x (latest)

* Updated `CTextFilter` with tags to be more like GitHub markdown.
* Added style to geshi sourcecode.

v0.3.93 (2013-04-09)

* Fixed installation phase.
* Corrected errors with configuration of clean urls.
* Separated style between lydia and dbwebb in base theme.

v0.3.92 (2013-03-25)

* `CForm` to differ on empty values to get null instead of empty.
* Translated to swedish.

v0.3.91 (2013-03-22)

* Preparing to release version 2 of dbwebb.se and including all modifications of Lydia.
* Several enhancements com `CMContent`.
* Added RSS feed and search to `CCBlog`.
* Added `CPageLoader` and `CMBlock`.
* Upgrader SimplePie to 1.3.1.
* Added improved `CForm`.
* Refactored `CCUser`.
* New theme, base. To be the new base-theme and replace both `grid` and `core`.
* Installation phase added.
* Prepare `gettext` to work in non-gettext environment.
* Clean up by removing dependencies to dbwebb.se.
* Corrected bugs in `CCAllSeeingEye` and `CMRSSAggregation` when feed is empty.
* Translated to swedish. Not all strings are yet being translated though.

v0.3.07 (2012-09-04)

* alpha5 for dbwebb.se.
* Added more columns to footer in grid theme.

v0.3.06 (2012-09-04)

* alpha4 for dbwebb.se.
* Added textfilter [BASEURL] to make urls depended on [BASEURL] rather than hardcoded in text and articles.

v0.3.05 (2012-08-31)

* alpha3 for dbwebb.se.
* Added preg_match capability to routing table.
* Corrected. Menu to set selected when routing table is used for urls.
* Corrected. Show correct label in thumbnail blog page view.

v0.3.04 (2012-08-28)

* alpha2 for dbwebb.se.
* Added category for blog.
* Improved CCIndex as default controller.
* Improved caching with `style.php`.
* General improvements.

v0.3.03 (2012-08-24)

* alpha1 for dbwebb.se.
* Added site/functions.php and used it to show how to integrate Lydia and phpBB.

v0.3.02 (2012-08-24)

* Updated to latest version v0.3.8 of lessphp.
* Ability to protect pages.
* Creating directory `js` to store various JavaScript files that are included in Lydia.
* Adding JavaScript library Modernizr.


v0.3.01 (2012-08-20)

* Use as basis for website http://dbwebb.se as a live testcase. Still internal use.
* Included new version of SimplePie 1.3.
* Enabling multilanguage, i18n, internationalization, though GNU's `gettext()`.
* Adding `t()` as the translation function to use.
* All language files in `language`. One .pot-file for all Lydia.
* Partly translated some modules to swedish for test.
* Adding module `CCAllSeeingEye` as a Lydia application for presenting aggregated RSS feed.
* Moving views to `views`-directory, can be overidden by placing view in `site/views`.
* Use `LoadView()` to load a view.
* Included PHP Markdown 1.0.1o, released 2012-01-08.
* Included PHP Markdown Extra 1.2.5, released 2012-01-08.
* Moved `CHTMLPurifier` to `CTextFilter`.
* Included PHP SmartyPants and PHP Typographer in `CTextFilter`.
* Many more changes such as `CMBook`, `CInterceptionFilter`, managing of modules and more.
* to many changes to mension, I need to get a stable base again when the site dbwebb.se gets up and runs this version of Lydia...


v0.3.0 (2012-05-23)

* Preparing to use Lydia in live site to show off RSS aggregation.
* Adding SimplePie version 1.2.1. 
* Corrected `CRequest` to handle requests `index/method` which failed before.
* adding factory wrapper for external RSS aggregators, including `Simplepie`
* adding model for RSS aggregator
* adding view-directory in site
* added method for `CreateUrlToController()`
* added method for displaying error codes 404, 403 using `ShowErrorPage()`
* added config-item for debug timer
* added `site_url` in `CRequest`
* added `CreateBreadcrumb()` and keep track of memory and timeusage through page relaods.
* added `get_language()`
* added `teaser()` and `slugify()`
* modified `theme_url()` and `theme_parent_url()` to use `site_url` when url starts with /
* corrected visible newline character in `user/profile`.
* corrected: changed  order when drawing menues to regions.


v0.2.24 (2012-05-07)

* Last change while in the phpmvc-course.
* Updating `CMModules` and `CCModules` to display information on a module's main class
using Reflection API.


v0.2.23 (2012-05-07)

* Creating menues through `site/config.php`.
* Mapping menus to theme regions.
* Child theme in `site/themes`.
* Site specific controllers/models/modules in `site/src`.
* Sample website in `site`.
* Added delete of content.


v0.2.22 (2012-05-02)

* Enhanced installation procedure by adding one controller for installation, `module/install`.
* Added interface `IModule` for all modules to implement that can manage themself for install/update/delete.


v0.2.21 (2012-05-02)

* Added module management through `CMModules` and `CCModules`.


v0.2.20 (2012-04-23)

* Moved static data from `themes/grid/functions.php` to `site/config.php`.
* Added `themes/functions.php get_tools()` for a list of useful debug tools.


v0.2.19 (2012-04-23)

* Added typography matching vertical grid to `theme/grid`.
* Added reset.css.


v0.2.18 (2012-04-23)

* Updated `CViewContainer` and related items to map views to regions in theme.


v0.2.17 (2012-04-23)

* Integrated with semantic.gs though the new theme `theme/grid`.
* Included sematic.gs version 1.2 (2012-01-11).


v0.2.16 (2012-04-19)

* Integrated with `lessphp` version 0.3.4-2 (2012-04-17) and LESS though a new theme, `theme/grid`.


v0.2.15 (2012-04-16)

* Added filter to format `CMContent` as 'htmlpurify' using http://htmlpurifier.org/.
* Added external library `htmlpurifier-4.4.0-standalone` in wrapper `CHTMLPurifier`.
* Added LICENSE.txt as MIT-license.


v0.2.14 (2012-04-16)

* Added filter to format `MCContent` as 'bbcode'.
* Added function `bbcode2html()` in `src/bootstrap.php`.


v0.2.13 (2012-04-13)

* Added filter to format `CMContet` as 'plain', 'html' and 'php'. 'html' and 'php' is disabled in `CMContent` as default. 
* Added function `makeClickable()` in `src/bootstrap.php` which formats links in text to <a>.


v0.2.12 (2012-04-13)

* Added `CCBlog` to display all content of type 'post' in a blog-like list.
* Added `CCPage` to display content of type 'page' in a singel-page view.
* Added formatting of DateTime since using function `formatDateTimeDiff()` and theme helper `time_diff()`.
* Lydia always set timezone to UTC. All time values stored in database is in UTC. 


v0.2.11 (2012-04-11) 

* Added handling for content with `CMContent` and `CCContent`.


v0.2.10 (2012-03-29) 

* Created a sequence to create a new user `user/create` using the web as userinterface.
* Corrected: CreateUrl in CCobject did not return its result.


v0.2.09 (2012-03-28) 

* Added server-side form validation to `CForm`.


v0.2.08 (2012-03-28) 

* Changed name of database class from `CMDatabase` to `CDatabase`.
* Added hashing techniques for storing password. plain, md5, sha1, md5salt, sha1salt.
* Made sha1salt the default algorithm when storing passwords.


v0.2.07 (2012-03-27) 

* Integrated with gravatar.com and created theme function to get the gravatar link.


v0.2.06 (2012-03-25) 

* Added classes for CForm, `CFormElement`, `CFormElementText`, `CFormElementPassword`, `CFormElementSubmit`
* Made `CForm` use `CFormElement` and both implements ArrayAccess.
* User can save profile on user/profile.
* `CMUser` uses implements ArrayAccess.


v0.2.05 (2012-03-21) 

* Corrected: Handling of incoming urls based on ?q=controller/metod and index.php/controller/metod.
* Added utility class CForm for form handling.
* Added login-form on user/login.


v0.2.04 (2012-03-19) 

* Used Reflection API to show the available controllers and methods using the index controller.


v0.2.03 (2012-03-19) 

* Made `CMUser` a part of `CLydia` and `CObject`.
* Created theme function `login_menu()`.
* Created controller `CCUserControlPanel`.


v0.2.02 (2012-03-15) 

* Uppgraded `CMUser` with groups and group-membership for each user.


v0.2.01 (2012-03-15) 

* Added `CMUser` and `CCUser` to handle users. Created code for testing login and logout of user.
* Added inteface convenience methods `RedirectToController` and `RedirectToControllerMethod` in `CObject`.


v0.1.9 (2012-03-13) 

* Added `CMGuestbook` as a model for the sample guestbook application.
* Rewrote code and separated between guestbook controller and model.


v0.1.8 (2012-03-05) 

* Added `CSession` as a wrapper to `$_SESSION`.
* Enabled flashmemory in `CSession` for as a memory to live through pagerequests, useful to send 
feedback to users and showing debuginfo to the developer.
* Updated `Guestbook` to make use of the new features.


v0.1.7 (2012-02-24) 

* Added a container for the views `CViewContainer`.
* Integrated `CViewContainer` in `CCGuestbook`, `CLydia`, `CObject`, and the theme handling.
* This makes `$ly->data` obsolete and to be removed in coming releases.


v0.1.6 (2012-02-24) 

* Added interface to those classes that uses SQL, `IUseSQL`.
* Updated `CCGuestbook` to use `IUseSQL`.


v0.1.5 (2012-02-24) 

* Introduced the database layer as `CMDatabase`.
* Showed how to use it in the `CCGuestbook` controller example.
* Enabled debug output from the database operations using settings in config-file and theme-helper 
`get_debug()`.
* Created a default exception handler in `bootstrap.php`.
* `CLydia` creates and owns the databaseobject as `$ly->db`.
* `CObject` makes the database object available for subclasses through `$this->db`.


v0.1.4 (2012-02-23) 

* Improved guestbook example to store messages in database using PHP PDO and SQLite.
* Added konfigurationsitem fpr database in site/config.php.
* Added site/data directory which should be writable bu the webserver.


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
* Wrapper htmlent() for htmlentities() to support character encoding from site/config.php.
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
..:  Copyright 2012 by Mikael Roos (me@mikaelroos.se)
