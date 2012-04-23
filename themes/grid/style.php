<?php
/**
 * Compiles a less-file to a css-file using phpless.
 *
 * Uses a cache-file before compiling. Uses gzip. Caches the resulting css-file by using a HTTP-
 * header with Last-Modified.
 * Read more on less: http://lesscss.org/
 * Read more on lessphp: http://leafo.net/lessphp/
 * The code below were taken from the following two tutorials and then slightly modified to suite
 * my needs:
 * http://leafo.net/lessphp/docs/#php_interface
 * http://net.tutsplus.com/tutorials/php/how-to-squeeze-the-most-out-of-less/
 *
 * @author Mikael Roos mos@dbwebb.se
 * @example http://dbwebb.se/example/lessphp/
 * @link https://github.com/mosbth/Utility/blob/master/style.php
 */
// __DIRNAME__only available from PHP 5.3 and forward
if(!defined('__DIR__')) define('__DIR__', dirname(__FILE__));
  
// Include the lessphp-compiler
include __DIR__."/lessphp/lessc.inc.php";

// Use gzip if available
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) 
  ob_start("ob_gzhandler");  
else 
  ob_start();  


/**
 * Compile less to css. Creates a cache-file of the last compiled less-file.
 *
 * This code is originally from the manual of lessphp.
 *
 * @param @less_fname string the filename of the less-file.
 * @param @css_fname string the filename of the css-file.
 * @param @cache_ext string the file-extension of the cache-file, added to the less filename. Default is '.cache'.
 * @returns boolean true if the css-file was changed, else returns false.
 */
function auto_compile_less($less_fname, $css_fname, $cache_ext='.cache') {
  $cache_fname = $less_fname.$cache_ext;
  if (file_exists($cache_fname)) {
    $cache = unserialize(file_get_contents($cache_fname));
  } else {
    $cache = $less_fname;
  }

  $new_cache = lessc::cexecute($cache);
  if (!is_array($cache) || $new_cache['updated'] > $cache['updated']) {
    file_put_contents($cache_fname, serialize($new_cache));
    file_put_contents($css_fname, $new_cache['compiled']);
    return true;
  }
  return false;
}


// Compile and output the resulting css-file, use caching whenever suitable.
$less = 'style.less';
$css  = 'style.css';
$cache_extension = '.cache';
$changed = auto_compile_less($less, $css, $cache_extension);
$time = mktime(0,0,0,21,5,1980); 
if(!$changed && isset($_SERVER['If-Modified-Since']) && strtotime($_SERVER['If-Modified-Since']) >= $time){  
  header("HTTP/1.0 304 Not Modified");  
} else {  
  header('Content-type: text/css');  
  header('Last-Modified: ' . gmdate("D, d M Y H:i:s",$time) . " GMT");  
  readfile($css);  
}  
