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
    'a-list-apart' => array('name'=>'A List Apart', 'feedurl'=>'http://www.alistapart.com/site/rss', 'siteurl'=>'http://www.alistapart.com/',),
    'html5-doctor' => array('name'=>'HTML5 Doctor', 'feedurl'=>'http://feeds.feedburner.com/html5doctor', 'siteurl'=>'http://html5doctor.com/',),
    'nettuts' => array('name'=>'Nettuts+', 'feedurl'=>'http://feeds.feedburner.com/nettuts', 'siteurl'=>'http://net.tutsplus.com/',),
    'sitepoint' => array('name'=>'Sitepoint', 'feedurl'=>'http://feeds.feedburner.com/SitepointFeed', 'siteurl'=>'http://www.sitepoint.com/',),
    'smashing-magazine' => array('name'=>'Smashing Magazine', 'feedurl'=>'http://rss1.smashingmagazine.com/feed/', 'siteurl'=>'http://www.smashingmagazine.com/',),
    
    // Category: Boilerplate, MVC & CMS
    //'html5boilerplate' => array('name'=>'HTML5Boilerplate', 'feedurl'=>'http://html5boilerplate.com/', 'siteurl'=>'http://html5boilerplate.com/',),
    'wordpress' => array('name'=>'WordPress', 'feedurl'=>'http://feeds.feedburner.com/wordpress/', 'siteurl'=>'http://wordpress.org/',),
    'drupal' => array('name'=>'Drupal', 'feedurl'=>'http://drupal.org/node/feed', 'siteurl'=>'http://drupal.org/',),
    'phpbb' => array('name'=>'phpBB', 'feedurl'=>'http://blog.phpbb.com/feed/', 'siteurl'=>'http://www.phpbb.com/',),
    'codeigniter' => array('name'=>'CodeIgniter', 'feedurl'=>'http://codeigniter.com/feeds/rss/news/', 'siteurl'=>'http://codeigniter.com/',),
    'joomla' => array('name'=>'Joomla', 'feedurl'=>'http://feeds.joomla.org/JoomlaAnnouncements', 'siteurl'=>'http://www.joomla.org/',),

    // Category: PHP    
    'php' => array('name'=>'PHP', 'feedurl'=>'http://php.net/', 'siteurl'=>'http://php.net/',),
    'simplepie' => array('name'=>'SimplePie', 'feedurl'=>'http://simplepie.org/blog/feed/', 'siteurl'=>'http://simplepie.org/',),
    'htmlpurifier' => array('name'=>'HTMLPurifier', 'feedurl'=>'http://htmlpurifier.org/news/', 'siteurl'=>'http://htmlpurifier.org/',),

    // Category: JavaScript
    'jquery' => array('name'=>'jQuery', 'feedurl'=>'http://feeds.feedburner.com/jquery/', 'siteurl'=>'http://jquery.com/',),
    'modernizr' => array('name'=>'Modernizr', 'feedurl'=>'http://feeds.feedburner.com/Modernizr', 'siteurl'=>'http://modernizr.com/',),

    // Category: Browsers and compatibility
    'can-i-use' => array('name'=>'Can I Use', 'feedurl'=>'http://feeds.feedburner.com/WhenCanIUse', 'siteurl'=>'http://caniuse.com/',),

    // Category: Profiler
    'eric-a-meyer' => array('name'=>'Erik A Meyer', 'feedurl'=>'http://meyerweb.com/eric/thoughts/category/tech/rss2/full/', 'siteurl'=>'http://meyerweb.com/',),

    // Category: Studenter och webbprojekt
    'olof-fredriksson' => array('name'=>'Olof Fredriksson', 'feedurl'=>'http://olof.nu/blogg/feed/', 'siteurl'=>'http://olof.nu/',),
    //'dirble' => array('name'=>'Dirble', 'feedurl'=>'http://dirble.com/', 'siteurl'=>'http://dirble.com/',),
    //frw
    'programvaruteknik-vid-bth' => array('name'=>'Programvaruteknik vid BTH', 'feedurl'=>'http://programvaruteknik.blogspot.com/feeds/posts/default', 'siteurl'=>'http://programvaruteknik.blogspot.com/',),

    // Category: Webdeveloper and API

    'mozilla-developers-network' => array('name'=>'Mozilla Developers Network', 'feedurl'=>'http://hacks.mozilla.org/', 'siteurl'=>'http://hacks.mozilla.org/',),
    'opera-dev' => array('name'=>'Opera Dev', 'feedurl'=>'http://my.opera.com/ODIN/xml/rss/blog/', 'siteurl'=>'http://my.opera.com/ODIN/blog/',),
    'facebook-utvecklare' => array('name'=>'Facebook utvecklare', 'feedurl'=>'http://developers.facebook.com/blog/feed', 'siteurl'=>'http://developers.facebook.com/',),
    'twitter-utvecklare' => array('name'=>'Twitter utvecklare', 'feedurl'=>'https://dev.twitter.com/blog/feed', 'siteurl'=>'https://dev.twitter.com/',),

    // Category: Websites in production
    'google-webmaster-central' => array('name'=>'Google Webmaster Central', 'feedurl'=>'http://googlewebmastercentral.blogspot.com/atom.xml', 'siteurl'=>'http://googlewebmastercentral.blogspot.com/',),
  ),
);

/* more potential feeds:
    // http://www.delicious.com/v2/rss/mikaelroos

*/
