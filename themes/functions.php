<?php
/**
 * Helpers for theming, available for all themes in their template files and functions.php.
 * This file is included right before the themes own functions.php
 */
 

/**
 * Print debuginformation from the framework.
 */
function get_debug() {
  // Only if debug is wanted.
  $ly = CLydia::Instance();  
  if(empty($ly->config['debug'])) {
    return;
  }
  
  // Get the debug output
  $html = null;
  if(isset($ly->config['debug']['timer']) && $ly->config['debug']['timer']) {
    $now = microtime(true);
    $flash = $ly->session->GetFlash('timer');
    $redirect = $flash ? round($flash['redirect'] - $flash['first'], 5)*1000 . ' msecs + x + ' : null;
    $total = $flash ? round($now - $flash['first'], 5)*1000 . ' msecs. Per page: ' : null;
    $html .= "<p>Page was loaded in {$total}{$redirect}" . round($now - $ly->timer['first'], 5)*1000 . " msecs.</p>";
  }    
  if(isset($ly->config['debug']['memory']) && $ly->config['debug']['memory']) {
    $flash = $ly->session->GetFlash('memory');
    $flash = $flash ? round($flash/1024/1024, 2) . ' Mbytes + ' : null;
    $html .= "<p>Peek memory consumption was $flash" . round(memory_get_peak_usage(true)/1024/1024, 2) . " Mbytes.</p>";
  }    
  if(isset($ly->config['debug']['db-num-queries']) && $ly->config['debug']['db-num-queries'] && isset($ly->db)) {
    $flash = $ly->session->GetFlash('database_numQueries');
    $flash = $flash ? "$flash + " : null;
    $html .= "<p>Database made $flash" . $ly->db->GetNumQueries() . " queries.</p>";
  }    
  if(isset($ly->config['debug']['db-queries']) && $ly->config['debug']['db-queries'] && isset($ly->db)) {
    $flash = $ly->session->GetFlash('database_queries');
    $queries = $ly->db->GetQueries();
    if($flash) {
      $queries = array_merge($flash, $queries);
    }
    if(!empty($queries)) {
      $html .= "<p>Database made the following queries.</p><pre>" . implode('<br/><br/>', $queries) . "</pre>";
    }
  }    
  if(isset($ly->config['debug']['lydia']) && $ly->config['debug']['lydia']) {
    $html .= "<hr><h3>Debuginformation</h3><p>The content of CLydia:</p><pre>" . htmlent(print_r($ly, true)) . "</pre>";
  }    
  if(isset($ly->config['debug']['session']) && $ly->config['debug']['session']) {
    $html .= "<hr><h3>SESSION</h3><p>The content of CLydia->session:</p><pre>" . htmlent(print_r($ly->session, true)) . "</pre>";
    $html .= "<p>The content of \$_SESSION:</p><pre>" . htmlent(print_r($_SESSION, true)) . "</pre>";
  }    
  return "<div class='debug'>$html</div>";
}


/**
 * Get messages stored in flash-session.
 */
function get_messages_from_session() {
  $messages = CLydia::Instance()->session->GetMessages();
  $html = null;
  if(!empty($messages)) {
    foreach($messages as $val) {
      $valid = array('info', 'notice', 'success', 'warning', 'error', 'alert');
      $class = (in_array($val['type'], $valid)) ? $val['type'] : 'info';
      $html .= "<div class='$class'>{$val['message']}</div>\n";
    }
  }
  return $html;
}


/**
 * Login menu. Creates a menu which reflects if user is logged in or not.
 */
function login_menu() {
  $ly = CLydia::Instance();

  if(!isset($ly->config['menus']['login'])) {
    return null;
  }
  $menu = $ly->config['menus']['login'];

  if($ly->user->isAuthenticated()) {
    $item = $menu['items']['ucp'];
    //$items = "<a href='" . create_url($item['url']) . "' title='{$item['title']}'><img class='gravatar' src='" . get_gravatar(20) . "' alt=''> " . $ly->user['acronym'] . "</a> ";
    $items = "<a href='" . create_url($item['url']) . "' title='{$item['title']}'>" . $ly->user['acronym'] . " <img class='gravatar' src='" . get_gravatar(20) . "' alt='[avatar]'></a>";
    
    if($ly->user['hasRoleAdministrator']) {
      $item = $menu['items']['acp'];
      $items .= "<a href='" . create_url($item['url']) . "' title='{$item['title']}'>{$item['label']}</a> ";
    }
    
    //$item = $menu['items']['logout'];
    //$items .= "<a href='" . create_url($item['url']) . "' title='{$item['title']}'>{$item['label']}</a> ";
  } else {
    $item = $menu['items']['login'];
    $items = "<a href='" . create_url($item['url']) . "' title='{$item['title']}'>{$item['label']}</a>";
  }

  $id    = isset($menu['id'])    ? " id='{$menu['id']}'" : null;
  $class = isset($menu['class']) ? " class='{$menu['class']}'" : null;
  return "<nav{$id}{$class}>$items</nav>";
}


/*
function login_menu() {
  $ly = CLydia::Instance();
  if(isset($ly->config['menus']['login'])) {
    if($ly->user->isAuthenticated()) {
      $item = $ly->config['menus']['login']['items']['ucp'];
      $items = "<a href='" . create_url($item['url']) . "' title='{$item['title']}'><img class='gravatar' src='" . get_gravatar(20) . "' alt=''> " . $ly->user['acronym'] . "</a> ";
      if($ly->user['hasRoleAdministrator']) {
        $item = $ly->config['menus']['login']['items']['acp'];
        $items .= "<a href='" . create_url($item['url']) . "' title='{$item['title']}'>{$item['label']}</a> ";
      }
      $item = $ly->config['menus']['login']['items']['logout'];
      $items .= "<a href='" . create_url($item['url']) . "' title='{$item['title']}'>{$item['label']}</a> ";
    } else {
      $item = $ly->config['menus']['login']['items']['login'];
      $items = "<a href='" . create_url($item['url']) . "' title='{$item['title']}'>{$item['label']}</a> ";
    }
    return "<nav>$items</nav>";
  }
  return null;
}
*/


/**
 * Format a date according the collate.
 *
 * @param string $date a date for format.
 * @return string for the formatted date.
 */
function format_date($date) {
  $locale = CLydia::Instance()->config['language'];
  $ftm = new IntlDateFormatter($locale, IntlDateFormatter::LONG, IntlDateFormatter::NONE);
  return $ftm->format(strtotime($date));
}


/**
 * Return the title of the page.
 *
 * @return string for title.
 */
function get_title() {
  return CLydia::Instance()->views->GetVariable('title');
}


/**
 * Return the meta content the page, if it exists, either as a theme variable or from the config.
 *
 * @param string for meta element.
 * @return string for meta element.
 */
function get_meta($what) {
  global $ly;
  $variable = "meta_{$what}";
  $meta1 = $ly->views->GetVariable($variable);
  $meta2 = isset($ly->config[$variable]) ? $ly->config[$variable] : null;
  $meta = isset($meta1) ? $meta1 : (isset($meta2) ? $meta2 : null);
  return isset($meta) ? "<meta name='{$what}' content='{$meta}'/>\n" : null;
}



/**
 * Get the feed if it has one.
 *
 * @return string for the alternate link.
 */
function get_feed() {
  global $ly;
  $feed   = $ly->views->GetVariable('alternate_feed');
  $title  = $ly->views->GetVariable('alternate_feed_title');
  return isset($feed) ? "<link rel='alternate' type='application/rss+xml' href='{$feed}' title='{$title}'>\n" : null;
}



/**
 * Add classes to html element.
 *
 * @return string with classes.
 */
function html_classes() {
  global $ly;
  if(isset($ly->config['theme']['html_class'])) {
    return $ly->config['theme']['html_class'];
  }
  return null;
}


/**
 * Create menu.
 *
 * @param array $menu array with details to generate menu.
 * @return string with formatted HTML for menu.
 */
function create_menu($menu) {
  return CLydia::Instance()->CreateMenu($menu);
}


/**
 * Get a gravatar based on the user's email.
 */
function get_gravatar($size=null) {
  return 'http://www.gravatar.com/avatar/' . md5(strtolower(trim(CLydia::Instance()->user['email']))) . '.jpg?r=pg&amp;d=wavatar&amp;' . ($size ? "s=$size" : null);
}


/**
 * Get language as defined in config.
 *
 * @returns string the language.
 */
function get_language() {
  return CLydia::Instance()->config['language'];
}


/**
 * Get pagination sequence.
 *
 * @returns string with li as pagination items.
 */
function pagination($start, $current, $last, $url) {
  if($start == $last) return null; 

  $p = "<ul class='pagination'>\n";
  
  if($start + 2 < $current) $p .= "\t<li><a href='{$url}?p={$first}'>&lt;&lt;</a></li>\n";
  if($start < $current)     $p .= "\t<li><a href='{$url}?p=".($current-1)."'>&lt;</a></li>\n";

  for($i=$start; $i <= $last; $i++) {
    if($i == $current) {
      $p .= "\t<li>$i</li>\n";
    } else {
      $p .= "\t<li><a href='{$url}?p={$i}'>$i</a></li>\n";
    }
  }

  if($current < $last)     $p .= "\t<li><a href='{$url}?p=".($current+1)."'>&gt;</a></li>\n";
  if($current + 2 < $last) $p .= "\t<li><a href='{$url}?p={$last}'>&gt;&gt;</a></li>\n";

  return $p . "\n</ul>";
}


/**
 * Get pagination next and previous, a bit more slim than pagination().
 *
 * @returns string with li as pagination items.
 */
function pagination_next($start, $current, $last, $firstHit, $lastHit, $totalHits, $url) {
  if($start == $last) return null; 

  $default = array(
    'text_previous' => t('&lt; Previous'),
    'text_next'     => t('Next &gt;'),
  );
  $options = $default;

  $p = "<ul class='pagination'>\n";
  if($start < $current) $p .= "\t<li><a href='{$url}?p=".($current-1)."'>{$options['text_previous']}</a></li>\n";
  //$p .= "\t<li>" . t('Display !firstHit-!lastHit (!totalHits)', array('!firstHit' => $firstHit, '!lastHit' => $lastHit, '!total_hits' => $totalHits)) . "</li>\n";
  if($current < $last) $p .= "\t<li><a href='{$url}?p=".($current+1)."'>{$options['text_next']}</a></li>\n";

  return $p . "\n</ul>";
}


/**
 * Escape data to make it safe to write in the browser.
 *
 * @param $str string to escape.
 * @return string the escaped string.
 */
function esc($str) {
  return htmlEnt($str);
}


/**
 * Filter data according to a filter. Uses CMContent::Filter()
 *
 * @param $data string the data-string to filter.
 * @param $filter string the filter to use.
 * @return string the filtered string.
 */
function filter_data($data, $filter) {
  return CMContent::Filter($data, $filter);
}


/**
 * Display diff of time between now and a datetime. 
 *
 * @param $start datetime|string
 * @return string
 */
function time_diff($start) {
  return formatDateTimeDiff($start);
}


/**
 * Prepend the base_url.
 */
function base_url($url=null) {
  return CLydia::Instance()->request->base_url . trim($url, '/');
}


/**
 * Create a url to an internal resource.
 *
 * @param string the whole url or the controller. Leave empty for current controller.
 * @param string the method when specifying controller as first argument, else leave empty.
 * @param string the extra arguments to the method, leave empty if not using method.
 */
function create_url($urlOrController=null, $method=null, $arguments=null) {
  return CLydia::Instance()->CreateUrl($urlOrController, $method, $arguments);
}


/**
 * Prepend the theme_url to non-absolute urls, theme_url is the url to the current theme directory.
 *
 * @param $url string the url-part to prepend.
 * @return string the absolute url.
 */
function theme_url($url) {
  if(!empty($url) && $url[0] == '/') 
    return create_url(CLydia::Instance()->request->site_url . "{$url}");
  return create_url(CLydia::Instance()->themeUrl . "/{$url}");
}



/**
 * Prepend the theme_parent_url to non-absolute urls, theme_parent_url is the url to the parent theme directory.
 *
 * @param $url string the url-part to prepend.
 * @return string the absolute url.
 */
function theme_parent_url($url) {
  if(!empty($url) && $url[0] == '/') 
    return create_url(CLydia::Instance()->request->site_url . "{$url}");
  return create_url(CLydia::Instance()->themeParentUrl . "/{$url}");
}



/**
 * Return the current url.
 */
function current_url() {
  return CLydia::Instance()->request->current_url;
}



/**
 * Render all views.
 *
 * @param $region string the region to draw the content in.
 */
function render_views($region='default') {
  return CLydia::Instance()->views->Render($region);
}



/**
 * Check if region has views. Accepts variable amount of arguments as regions.
 *
 * @param $region string the region to draw the content in.
 */
function region_has_content($region='default' /*...*/) {
  return CLydia::Instance()->views->RegionHasView(func_get_args());
}



/**
 * Get the class that may be attached to the region.
 *
 * @param $region string the region to draw the content in.
 * @param $region string the region to draw the content in.
 * @return mixed the resulting classes within a string with class='$classes' or null if no classes.
 */
function get_class_for_region($region, $classes=null) {
  $classes .= CLydia::Instance()->views->RegionHasClass($region);
  return isset($classes) ? " class='{$classes}'" : null;
}



/**
 * Create tracker if defined in config.php.
 *
 * Google Analytics: Example from HTML5Boilerplate and http://mathiasbynens.be/notes/async-analytics-snippet
 * Piwik: Use with Javascript-snippet.
 */
function get_tracker() {
  global $ly;
  $ga = $ly->config['google_analytics'];
  $pw = $ly->config['piwik'];
  $html = null;
  if($ga) {
    $html .= "<script>var _gaq=[['_setAccount','{$ga}'],['_trackPageview']];(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.src='//www.google-analytics.com/ga.js';s.parentNode.insertBefore(g,s)}(document,'script'))</script>\n";
  }
  if($pw) {
    $html .= <<<EOD
<!-- Piwik --> 
<script type="text/javascript">
var pkBaseURL = (("https:" == document.location.protocol) ? "https://{$pw}" : "http://{$pw}");
document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
</script><script type="text/javascript">
try {
var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 1);
piwikTracker.trackPageView();
piwikTracker.enableLinkTracking();
} catch( err ) {}
</script><noscript><p><img src="http://{$pw}piwik.php?idsite=1" style="border:0" alt="" /></p></noscript>
<!-- End Piwik Tracking Code -->
EOD;
  }
  return $html;
}


/**
 * Include the javascript library modernizer, if defined.
 *
 * @return string if modernizr path is defined, else null.
 */
function modernizr_include() {
  global $ly;
  return isset($ly->config['javascript']['modernizr']) ? "<script src='{$ly->config['javascript']['modernizr']}'></script>" : null;
}


/**
 * Add modernizer related class 'no-js' but only if modernizr is defined.
 *
 * @return string if modernizr is defined, else null.
 */
function modernizr_no_js() {
  global $ly;
  return isset($ly->config['javascript']['modernizr']) ? 'no-js' : null;
}



//
// OBSOLETE
//

/**
 * Does the site has a slogan to show? OBSOLETE use $slogan in template.
 */
function has_slogan() {
  global $ly;
  return isset($ly->config['theme']['data']['site-slogan']) && isset($ly->config['theme']['data']['show_slogan']) && $ly->config['theme']['data']['show_slogan'];
}


/**
 * Return the site slogan. OBSOLETE use $slogan in template.
 */
function slogan() {
  global $ly;
  return $ly->config['theme']['data']['site-slogan'];
}

