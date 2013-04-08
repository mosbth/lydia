<?php
/**
 * Site configuration, this file is changed by user per site.
 *
 */

/**
 * Set level of error reporting
 */
error_reporting(-1);
ini_set('display_errors', 1);


/**
 * Set what to show as debug or developer information in the get_debug() theme helper.
 */
$ly->config['debug']['lydia'] = false;
$ly->config['debug']['session'] = false;
$ly->config['debug']['timer'] = true;
$ly->config['debug']['memory'] = true;
$ly->config['debug']['db-num-queries'] = true;
$ly->config['debug']['db-queries'] = true;


/**
 * Set database(s).
 */
$ly->config['database'][0]['dsn'] = 'sqlite:' . LYDIA_DATA_PATH . '/.ht.sqlite';


/**
 * What type of urls should be used?
 * 
 * default      = 0      => index.php/controller/method/arg1/arg2/arg3
 * clean        = 1      => controller/method/arg1/arg2/arg3
 * querystring  = 2      => index.php?q=controller/method/arg1/arg2/arg3
 */
//$ly->config['url_type'] = 1;


/**
 * Set a base_url to use another than the default calculated
 */
$ly->config['base_url'] = null;


/**
 * How to hash password of new users, choose from: plain, md5salt, md5, sha1salt, sha1.
 */
$ly->config['hashing_algorithm'] = 'sha1salt';


/**
 * Allow or disallow creation of new user accounts. 
 * Set to false to disable that anyone can create a new user, set true to enable.
 */
$ly->config['create_new_users'] = false;


/**
 * Create a secret key and use it for remote management. Make it long through sha1 or
 * similair. Disable it by setting to null/false.
 *
 * Currently supporting:
 * module/action/crontab/[secret_key]
 */
$ly->config['secret_key'] = false;


/**
 * Define session name
 */
$ly->config['session_name'] = preg_replace('/[:\.\/-_]/', '', __DIR__);
$ly->config['session_key']  = 'lydia';


/**
 * Define default server timezone when displaying date and times to the user. All internals are still UTC.
 */
$ly->config['timezone'] = 'Europe/Stockholm';


/**
 * Define internal character encoding OBSOLETE? Done for $lydia in construktor instead.
 */
$ly->config['character_encoding'] = 'UTF-8';


/**
 * Define language
 *
 * langugage: the language of the webpage and locale, settings for i18n, 
 *            internationalization supporting multilanguage.
 * i18n: enable internationalization through gettext.
 */
$ly->config['language'] = 'en';
//$ly->config['i18n'] = true;
$ly->config['i18n'] = function_exists('gettext');


/**
 * Localise L10n method names to make search become sok (or sök) in Sweden.
 */
$ly->config['method_L10n'] = array(
  'sv' => array(
    'search'    => 'sok',
    'category'  => 'kategori',
  ),
);



/**
 * RSS feed settings. Change this to your own copyright notice for the RSS feeds.
 */
$ly->config['feed']['copyright'] = 'Copyright (C) Lydia';



/**
 * Define the controllers, their classname and enable/disable them.
 *
 * The array-key is matched against the url, for example: 
 * the url 'developer/dump' would instantiate the controller with the key "developer", that is 
 * CCDeveloper and call the method "dump" in that class. This process is managed in:
 * $ly->FrontControllerRoute();
 * which is called in the frontcontroller phase from index.php.
 */
$ly->config['controllers'] = array(
  'install'   => array('enabled' => true,'class' => 'CCInstall'),
  'index'     => array('enabled' => true,'class' => 'CCIndex'),
  'developer' => array('enabled' => true,'class' => 'CCDeveloper'),
  'theme'     => array('enabled' => true,'class' => 'CCTheme'),
  'guestbook' => array('enabled' => true,'class' => 'CCGuestbook'),
  'content'   => array('enabled' => true,'class' => 'CCContent'),
  'book'      => array('enabled' => true,'class' => 'CCBook'),
  'blog'      => array('enabled' => true,'class' => 'CCBlog'),
  'page'      => array('enabled' => true,'class' => 'CCPage'),
  'user'      => array('enabled' => true,'class' => 'CCUser'),
  'acp'       => array('enabled' => true,'class' => 'CCAdminControlPanel'),
  'module'    => array('enabled' => true,'class' => 'CCModules'),
  'my'        => array('enabled' => true,'class' => 'CCMycontroller'),
  'rss'       => array('enabled' => true,'class' => 'CCAllSeeingEye'),
);


/**
 * Define a routing table for urls.
 *
 * Route custom urls to a defined controller/method/arguments
 */
$ly->config['routing'] = array(
  //'home' => array('enabled' => true, 'url' => 'index/index'),
  'user' => array('enabled' => true, 'url' => 'user/overview'),
  //'#^kurser/(.+)$#' => array('preg' => true, 'enabled' => true, 'url' => 'index/courses'),
);


/**
 * Enable/disable pageloader, all urls that do not match controller or a routing entry will
 * be managed by CPageLoader.
 */
$ly->config['pageloader'] = true;
//$ly->config['pageloader_class'] = 'CMPageLoaderCustom';


/**
 * Append site label after all titels, seo related, so it looks nice in the search engine
 * results.
 */
$ly->config['title_append'] = 'lydia';
$ly->config['title_separator'] = ' - ';


/**
 * Extra parameters, useful sometimes or for adaptions.
 */
//$ly->config['extra']['phpbb_root_path'] = '/usr/home/mos/htdocs/dbwebb.se/forum/';


/**
 * Define what Javascript librarys to be included. Set the url to the source-file, use
 * relative link to be relative base_url, else set absolute url.
 */
$ly->config['javascript']['modernizr'] = 'js/modernizr/2.6.1_smallest.js';


/**
 * Use tracking to monitor usage of site, set to false to disable.
 * google_analytics: set to the tracker id.
 * piwik: set to sitename, including link to piwik installation, i.e. www.where.com/piwik, end with slash.
 */
$ly->config['google_analytics'] = false;
$ly->config['piwik'] = false;



/**
 * Define menus.
 *
 * Create hardcoded menus and map them to a theme region through $ly->config['theme'].
 */
$ly->config['menus'] = array(
  'login' => array(
    'id'    => 'login-menu',
    'class' => '',
    'items' => array(
      'login' => array('label'=>'login', 'url'=>'user/login', 'title'=>'Login'),
      'logout' => array('label'=>'logout', 'url'=>'user/logout', 'title'=>'Logout'),
      'ucp' => array('label'=>'ucp', 'url'=>'user', 'title'=>'User control panel'),
      'acp' => array('label'=>'acp', 'url'=>'acp', 'title'=>'Admin control panel'),
    ),
  ),
  'navbar-default' => array(
    'id'    => 'navbar-default',
    'class' => 'nb-anna',
    'items' => array(
      'home'      => array('label'=>'Home', 'url'=>''),
      'blog'      => array('label'=>'Blog', 'url'=>'blog'),
      'content'   => array('label'=>'Content', 'url'=>'content'),
      'rss'       => array('label'=>'RSS', 'url'=>'rss'),
      'theme'     => array('label'=>'Theme', 'url'=>'theme'),
      'modules'   => array('label'=>'Modules', 'url'=>'module'),
    ),
  ),
  'navbar-ucp' => array(
    'id'    => 'navbar-ucp',
    'class' => 'nb-tab',
    'items' => array(
      'overview'  => array('label'=>t('Overview'),  'url'=>'user/overview'),
      'content'   => array('label'=>t('Content'),   'url'=>'user/content'),
      'profile'   => array('label'=>t('Profile'),   'url'=>'user/profile'),
      'mail'      => array('label'=>t('Groups'),    'url'=>'user/groups'),
      'groups'    => array('label'=>t('Mail'),      'url'=>'user/email'),
      'password'  => array('label'=>t('Password'),  'url'=>'user/change-password'),
    ),
  ),
);



/**
 * Settings for the theme. The theme may have a parent theme.
 *
 * When a parent theme is used the parent's functions.php will be included before the current
 * theme's functions.php. The parent stylesheet can be included in the current stylesheet
 * by an @import clause. See site/themes/mytheme for an example of a child/parent theme.
 * Template files can reside in the parent or current theme, the CLydia::ThemeEngineRender()
 * looks for the template-file in the current theme first, then it looks in the parent theme.
 *
 * There are two useful theme helpers defined in themes/functions.php.
 *  theme_url($url): Prepends the current theme url to $url to make an absolute url. 
 *  theme_parent_url($url): Prepends the parent theme url to $url to make an absolute url. 
 *
 * path: Path (on disk) to the directory holding the theme.
 * url: The url to the theme directory, relative to base_url or absolute (starts with /).
 * parent-path: Path (on disk) to parent theme. Can be left out or set to null.
 * stylesheet: The stylesheet to include, always part of the current theme, use @import to include the parent stylesheet.
 * template_file: Set the default template file, defaults to index.tpl.php.
 * regions: Array with all regions that the theme supports.
 * menu_to_region: Array mapping menus to regions.
 * data: Array with data that is made available to the template file as variables. 
 * 
 * The name of the stylesheet is also appended to the data-array, as 'stylesheet' and made 
 * available to the template files.
 */
$ly->config['theme'] = array(
  'html_class'    => 'lydia',
  'name'          => 'base',
  'path'          => LYDIA_INSTALL_PATH . '/themes/base',
  'url'           => 'themes/base',
  //'parent'        => LYDIA_INSTALL_PATH . '/themes/grid', 
  //'parent-url'    => 'themes/grid', 
  //'stylesheet'    => 'style.css',
  'stylesheet'    => 'style/style.php',
  //'template_file' => 'index.tpl.php',
  'regions' => array('site-menu', 'breadcrumb', 'navbar', 'flash','featured-first','featured-middle','featured-last',
    'primary','sidebar','triptych-first','triptych-middle','triptych-last',
    'footer-column-one','footer-column-two','footer-column-three','footer-column-four','footer-column-five',
    'footer',
  ),
  'region_to_menu' => array(
    'navbar1' => 'navbar-default', 
  //  'navbar2'=>'navbar-default1',
  ),
  'data' => array(
    'site_title' => 'Lydia',
    'site_slogan' => 'A PHP-based MVC-inspired CMF',
    'favicon' => 'img/logo_80x80.png',
    //'site_logo' => 'logo_80x80.png',
    //'site_logo_alt' => 'logo',
    //'site_logo_width'  => 80,
    //'site_logo_height' => 80,
    //'custom_banner' => 'custom',
  ),
  'view_to_region' => array(
    array('region' => 'footer', 'type' => 'string', 'content' => "<p><code style='position:relative;'>..:<span style='position:relative;top:-8px;left:-14px;'>.</span></code>&nbsp;&nbsp;Copyright &copy; <a class='no-style' href='http://mikaelroos.se'>Mikael Roos</a> (me@mikaelroos.se) &nbsp;&nbsp;|&nbsp;&nbsp; Ronneby &bull; Bankeryd &bull; Sweden &nbsp;&nbsp;|&nbsp;&nbsp; <em><a class='no-style' href='http://dbwebb.se/lydia/'>Lydia</a> is a brainchild of <a class='no-style' href='http://dbwebb.se/'>dbwebb</a>.</em></p>"),
  /*  array('region' => 'footer-column-one',   'type' => 'include', 'content' => 'themes-grid/footer_column_one.tpl.php'),
    array('region' => 'footer-column-two',   'type' => 'include', 'content' => 'themes-grid/footer_column_two.tpl.php'),
    array('region' => 'footer-column-three', 'type' => 'include', 'content' => 'themes-grid/footer_column_three.tpl.php'),
    array('region' => 'footer-column-four',  'type' => 'include', 'content' => 'themes-grid/footer_column_four.tpl.php'),
    array('region' => 'footer-column-five',  'type' => 'include', 'content' => 'themes-grid/footer_column_five.tpl.php'),
    array('region' => 'footer-column-six',   'type' => 'include', 'content' => 'themes-grid/footer_column_six.tpl.php'),
    array('region' => 'footer-column-seven', 'type' => 'include', 'content' => 'themes-grid/footer_column_seven.tpl.php'),
    array('region' => 'footer-column-eight', 'type' => 'include', 'content' => 'themes-grid/footer_column_eight.tpl.php'),
    array('region' => 'footer-column-nine',  'type' => 'include', 'content' => 'themes-grid/footer_column_nine.tpl.php'),
  */),
);
