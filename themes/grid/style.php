<?php
/**
 * Autocompiles a less-file to a css-file using phpless.
 *
 * Uses a cache-file before compiling. Uses gzip. Caches the resulting css-file by using a HTTP-
 * header with Last-Modified.
 * Read more on lessphp: http://leafo.net/lessphp/
 * Read more on less: http://lesscss.org/
 *
 * @author Mikael Roos mos@dbwebb.se
 * @example http://dbwebb.se/kod-exempel/lessphp/
 * @link https://github.com/mosbth/stylephp
 *
 * 2012-08-27: 
 * Changed time() to gmtime() to make 304 work.
 *
 * 2012-08-21: 
 * Uppdated with lessphp v0.3.8, released 2012-08-18. 
 * Corrected gzip-handling and caching using Not Modified.
 *
 * 2012-04-18: First try.
 *
 */
// Include the lessphp-compiler
include dirname(__FILE__)."/lessphp/lessc.inc.php";

// Use gzip if available
ob_start("ob_gzhandler") or ob_start();


/**
 * Compile less to css. Creates a cache-file of the last compiled less-file.
 *
 * This code is originally from the manual of lessphp.
 *
 * @param string $inputFile the filename of the less-file.
 * @param string $outputFile the filename of the css-file to be created.
 */
function autoCompileLess($inputFile, $outputFile) {
  $cacheFile = $inputFile.".cache";

  if (file_exists($cacheFile)) {
    $cache = unserialize(file_get_contents($cacheFile));
  } else {
    $cache = $inputFile;
  }

  $less = new lessc;
  $newCache = $less->cachedCompile($cache);

  if (!is_array($cache) || $newCache["updated"] > $cache["updated"]) {
    file_put_contents($cacheFile, serialize($newCache));
    file_put_contents($outputFile, $newCache['compiled']);
  }
}


// Compile and output the resulting css-file, use caching whenever suitable.
$less = 'style.less';
$css  = 'style.css';
$changed = autoCompileLess($less, $css);
$time = filemtime($css);

// Write it out and leave a response
if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $time){  
  header("HTTP/1.0 304 Not Modified");  
} else {  
  header('Content-type: text/css');  
  header('Last-Modified: ' . gmdate("D, d M Y H:i:s", $time) . " GMT");  
  readfile($css);  
}  
