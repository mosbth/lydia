<?php
/**
 * Array with all feeds to be monitored and managed.
 */
$feeds = array(
  'dbwebb' => array(
    'name' => 'Webbprogrammering med Databaser och HTML, CSS, JavaScript, PHP och SQL.', 
    'description' => 'Lär dig utveckla databasdrivna webbplatser med välkända och opensource-baserade verktyg och tekniker.',
    'sites' => array(
      'dbwebb-forum' => array('name'=>'dbwebb forum', 'feedurl'=>'http://dbwebb.se/forum/feed.php',),
      'dbwebb-blogg' => array('name'=>'dbwebb forum', 'feedurl'=>'http://dbwebb.se/blogg',),
    ),
  ),
);
