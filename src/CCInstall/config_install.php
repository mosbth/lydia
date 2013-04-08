<?php
/**
 * Changes for site configuration during installation phase.
 *
 */

/**
 * Set level of error reporting
 */
error_reporting(-1);
ini_set('display_errors', 1);


/**
 * Define menus.
 *
 * Create hardcoded menus and map them to a theme region through $ly->config['theme'].
 */
$ly->config['menus'] = null;
/*
$ly->config['menus'] = array(
  'login' => array(
    'login' => array('label'=>'login', 'url'=>'user/login', 'title'=>'Login'),
    'logout' => array('label'=>'logout', 'url'=>'user/logout', 'title'=>'Logout'),
    'ucp' => array('label'=>'ucp', 'url'=>'user', 'title'=>'User control panel'),
    'acp' => array('label'=>'acp', 'url'=>'acp', 'title'=>'Admin control panel'),
  ),
  'navbar' => array(
    'home'      => array('label'=>'Home', 'url'=>'home'),
    'modules'   => array('label'=>'Modules', 'url'=>'module'),
    'content'   => array('label'=>'Content', 'url'=>'content'),
    'guestbook' => array('label'=>'Guestbook', 'url'=>'guestbook'),
    'blog'      => array('label'=>'Blog', 'url'=>'blog'),
    'rss'       => array('label'=>'RSS', 'url'=>'rss'),
  ),
  'my-navbar' => array(
    'home'      => array('label'=>'About Me', 'url'=>'my'),
    'blog'      => array('label'=>'My Blog', 'url'=>'my/blog'),
    'guestbook' => array('label'=>'Guestbook', 'url'=>'my/guestbook'),
  ),
);
*/


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
$ly->config['theme'] = null;
$ly->config['theme'] = array(
  'html_class'    => 'lydia',
  'path'          => LYDIA_INSTALL_PATH . '/themes/base',
  'url'           => 'themes/base',
  //'parent'        => LYDIA_INSTALL_PATH . '/themes/grid', 
  //'parent-url'    => 'themes/grid', 
  'stylesheet'    => 'style/style.css',
  //'stylesheet'    => 'style.php',
  //'template_file' => 'index.tpl.php',
  'regions' => array('site-menu', 'breadcrumb', 'navbar', 'flash','featured-first','featured-middle','featured-last',
    'primary','sidebar','triptych-first','triptych-middle','triptych-last',
    'footer-column-one','footer-column-two','footer-column-three','footer-column-four','footer-column-five',
    'footer',
  ),
  'region_to_menu' => null, //array('site-menu'=>'my-navbar', 'navbar'=>'navbar'),
  'data' => array(
    'site_title' => 'Install Lydia',
    //'site_slogan' => 'A PHP-based MVC-inspired CMF',
    'favicon' => 'img/logo_80x80.png',
    //'site_logo' => 'logo_80x80.png',
    //'site_logo_alt' => 'logo',
    //'site_logo_width'  => 80,
    //'site_logo_height' => 80,
    //'custom_banner' => 'custom',
  ),
  'view_to_region' => array(
    array('region' => 'footer', 'type' => 'string', 'content' => "<p><code style='font-size:0.8em;line-height:1;'>&nbsp;.&nbsp;<br/>..:</code>&nbsp;&nbsp;Copyright &copy; <a class='no-style' href='http://mikaelroos.se'>Mikael Roos</a> (me@mikaelroos.se) &nbsp;&nbsp;|&nbsp;&nbsp; Ronneby &bull; Bankeryd &bull; Sweden &nbsp;&nbsp;|&nbsp;&nbsp; <em><a class='no-style' href='http://dbwebb.se/lydia/'>Lydia</a> is a brainchild of <a class='no-style' href='http://dbwebb.se/'>dbwebb</a>.</em></p>"),
    //array('region' => 'footer-column-five',  'type' => 'include', 'content' => 'themes-grid/footer_column_five.tpl.php'),
    //array('region' => 'footer-column-six',   'type' => 'include', 'content' => 'themes-grid/footer_column_six.tpl.php'),
    //array('region' => 'footer-column-seven', 'type' => 'include', 'content' => 'themes-grid/footer_column_seven.tpl.php'),
    //array('region' => 'footer-column-eight', 'type' => 'include', 'content' => 'themes-grid/footer_column_eight.tpl.php'),
  ),
);
