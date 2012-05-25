<?php
/**
 * Array with all feeds to be monitored and managed.
 */
$feeds = array(
  'categories' => array(
    'dbwebb' => array(
      'name' => t('dbwebb'), 
      'description' => t('Databases and Webbprogramming. Learn webdevelopment and be inspired from opensource. HTML, CSS, JavaScript, PHP, SQL, Unix.'),
      'sites' => array('dbwebb-blog', 'dbwebb-forum',),
    ),
  ),
  
  'sites' => array(
    // Category: Databases and Webbprogramming - dbwebb
    'dbwebb-blog' => array('name'=>t('dbwebb blogg'), 'feedurl'=>'http://dbwebb.se/blogg/feed', 'siteurl'=>'http://dbwebb.se/blogg/',),
    'dbwebb-forum' => array('name'=>t('dbwebb forum'), 'feedurl'=>'http://dbwebb.se/forum/feed.php', 'siteurl'=>'http://dbwebb.se/forum/',),
  ),
);
