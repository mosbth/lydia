<?php
/**
 * Array with all feeds to be monitored and managed.
 */
$feeds = array(
  'categories' => array(
    'artiklar-och-magasin' => array(
      'name' => 'Artiklar & Magasin', 
      'description' => 'Hämta inspiration och kunskap från artiklar och tutorials.',
      'sites' => array('a-list-apart', 'html5-doctor', 'nettuts',  'sitepoint', 'smashing-magazine',),
    ),
    'webb-profiler' => array(
      'name' => 'Profiler inom webbutveckling',
      'description' => 'Kända personer inom webbvärlden som bloggar och skriver kunskapsartiklar vid fronten av webbutveckling.',
      'sites' => array('eric-a-meyer',),
    ),
    'boilerplate-mvc-och-cms' => array(
      'name' => 'Boilerplate, MVC & CMS',
      'description' => 'Ramverk för att bygga din webbplats.',
      'sites' => array('wordpress', 'drupal', 'phpbb', 'codeigniter', 'joomla'),
    ),
    'php' => array(
      'name' => 'PHP',
      'description' => 'PHP och PHP-baserade libb.',
      'sites' => array('php', 'simplepie', 'htmlpurifier',),
    ),
    'javascript' => array(
      'name' => 'JavaScript',
      'description' => 'JavaScript och JS-libb.',
      'sites' => array('jquery', 'modernizr',),
    ),
    'webblasare-och-kompabilitet' => array(
      'name' => 'Webbläsare och kompabilitet',
      'description' => 'Koll på utveckling av webbläsare och kompabilitet mot specifikationerna.',
      'sites' => array('can-i-use',),
    ),
    'webbutvecklare-och-api' => array(
      'name' => 'Webbutvecklare och API',
      'description' => 'Populära webbplatsers utvecklingsdelar med publicerade API:er.',
      'sites' => array('mozilla-developers-network', 'opera-dev', 'facebook-utvecklare', 'twitter-utvecklare',),
    ),
    'webbmaster-drift-av-webbplats' => array(
      'name' => 'Webbplatsen i drift',
      'description' => 'Utveckla, följ upp och finjustera din webbplats.',
      'sites' => array('google-webmaster-central',),
    ),
    'studenter-och-webbprojekt' => array(
      'name' => 'Studenter och webbprojekt',
      'description' => 'Följer studenter som gått programmet Webbprogrammering och de som gått samtliga kurser i kursklustret dbwebb och som är aktiva inom dbwebb.se samt aktiva med något webbprojekt eller bloggar inom webbutveckling och webbprogrammering.',
      'sites' => array('programvaruteknik-vid-bth', 'olof-fredriksson',),
    ),
  ),
  
  'sites' => array(
    // Category: Articles and magazines
    'a-list-apart' => array('name'=>'A List Apart', 'feedurl'=>'http://www.alistapart.com/site/rss',),
    'html5-doctor' => array('name'=>'HTML5 Doctor', 'feedurl'=>'http://feeds.feedburner.com/html5doctor',),
    'nettuts' => array('name'=>'Nettuts+', 'feedurl'=>'http://feeds.feedburner.com/nettuts',),
    'sitepoint' => array('name'=>'Sitepoint', 'feedurl'=>'http://feeds.feedburner.com/SitepointFeed',),
    'smashing-magazine' => array('name'=>'Smashing Magazine', 'feedurl'=>'http://rss1.smashingmagazine.com/feed/',),
    
    // Category: Boilerplate, MVC & CMS
    //'html5boilerplate' => array('name'=>'HTML5Boilerplate', 'feedurl'=>'http://html5boilerplate.com/',),
    'wordpress' => array('name'=>'WordPress', 'feedurl'=>'http://feeds.feedburner.com/wordpress/',),
    'drupal' => array('name'=>'Drupal', 'feedurl'=>'http://drupal.org/node/feed',),
    'phpbb' => array('name'=>'phpBB', 'feedurl'=>'http://blog.phpbb.com/feed/',),
    'codeigniter' => array('name'=>'CodeIgniter', 'feedurl'=>'http://codeigniter.com/feeds/rss/news/',),
    'joomla' => array('name'=>'Joomla', 'feedurl'=>'http://feeds.joomla.org/JoomlaAnnouncements',),

    // Category: PHP    
    'php' => array('name'=>'PHP', 'feedurl'=>'http://php.net/',),
    'simplepie' => array('name'=>'SimplePie', 'feedurl'=>'http://simplepie.org/blog/feed/',),
    'htmlpurifier' => array('name'=>'HTMLPurifier', 'feedurl'=>'http://htmlpurifier.org/news/',),

    // Category: JavaScript
    'jquery' => array('name'=>'jQuery', 'feedurl'=>'http://feeds.feedburner.com/jquery/',),
    'modernizr' => array('name'=>'Modernizr', 'feedurl'=>'http://feeds.feedburner.com/Modernizr',),

    // Category: Browsers and compatibility
    'can-i-use' => array('name'=>'Can I Use', 'feedurl'=>'http://feeds.feedburner.com/WhenCanIUse',),

    // Category: Profiler
    'eric-a-meyer' => array('name'=>'Erik A Meyer', 'feedurl'=>'http://meyerweb.com/eric/thoughts/category/tech/rss2/full/',),

    // Category: Studenter och webbprojekt
    'olof-fredriksson' => array('name'=>'Olof Fredriksson', 'feedurl'=>'http://olof.nu/blogg/feed/',),
    //'dirble' => array('name'=>'Dirble', 'feedurl'=>'http://dirble.com/',),
    //frw
    'programvaruteknik-vid-bth' => array('name'=>'Programvaruteknik vid BTH', 'feedurl'=>'http://programvaruteknik.blogspot.com/feeds/posts/default',),

    // Category: Webdeveloper and API

    'mozilla-developers-network' => array('name'=>'Mozilla Developers Network', 'feedurl'=>'http://hacks.mozilla.org/',),
    'opera-dev' => array('name'=>'Opera Dev', 'feedurl'=>'http://my.opera.com/ODIN/xml/rss/blog/',),
    'facebook-utvecklare' => array('name'=>'Facebook utvecklare', 'feedurl'=>'http://developers.facebook.com/blog/feed',),
    'twitter-utvecklare' => array('name'=>'Twitter utvecklare', 'feedurl'=>'https://dev.twitter.com/blog/feed',),

    // Category: Websites in production
    'google-webmaster-central' => array('name'=>'Google Webmaster Central', 'feedurl'=>'http://googlewebmastercentral.blogspot.com/atom.xml',),
  ),
);

/* more potential feeds:
    // http://www.delicious.com/v2/rss/mikaelroos

*/
