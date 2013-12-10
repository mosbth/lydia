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
 * 2013-12-09: 
 * Added config for 'style_file' to change name of stylefile.
 *
 * 2013-10-28: 
 * Included lessphp 0.4.0.
 * Always send last-modified header.
 * Added style_config.php with configuration parameters.
 * Added less function for unit()
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

/**
 * Add config-file if available
 * 
 */
$configFile = dirname(__FILE__).'/style_config.php';
$config = array('path' => dirname(__FILE__)."/lessphp/lessc.inc.php");
if(is_file($configFile)) {
  include($configFile);
}
include($config['path']);


/**
 * Check settins before proceeding
 */
preg_match('#^[a-z0-9A-Z-_]+$#', $config['style_file']) or die('Filename for style_file contains invalid characters.');



/**
 * Compile less to css. Creates a cache-file of the last compiled less-file.
 *
 * This code is originally from the manual of lessphp.
 *
 * @param string $inputFile the filename of the less-file.
 * @param string $outputFile the filename of the css-file to be created.
 * @param array $config with configuration details.
 */
function autoCompileLess($inputFile, $outputFile, $config) {
  $cacheFile = $inputFile.".cache";

  if (file_exists($cacheFile)) {
    $cache = unserialize(file_get_contents($cacheFile));
  } else {
    $cache = $inputFile;
  }

  $less = new lessc;

  // Add custom less functions
  if(isset($config['functions'])) {
    foreach($config['functions'] as $key => $val) {
      $less->registerFunction($key, $val);
    }    
  }

  // Add import dirs
  if(isset($config['imports'])) {
    foreach($config['imports'] as $val) {
      $less->addImportDir($val);
    }    
  }

  // Set output formatter
  if(isset($config['formatter'])) {
    $less->setFormatter($config['formatter']);
  }

  // Preserve comments
  if(isset($config['comments'])) {
    $less->setPreserveComments($config['comments']);
  }

  // Compile a new cache
  $newCache = $less->cachedCompile($cache);
  if (!is_array($cache) || $newCache["updated"] > $cache["updated"]) {
    file_put_contents($cacheFile, serialize($newCache));
    file_put_contents($outputFile, $newCache['compiled']);
  }
}



//
// Compile and output the resulting css-file, use caching whenever suitable.
//

$less = $config['style_file'] . '.less';
$css  = $config['style_file'] . '.css';
$changed = autoCompileLess($less, $css, $config);
$time = filemtime($css);
$gmdate = gmdate("D, d M Y H:i:s", $time);



//
// Write it out and leave a response, use gzip if available
//
ob_start("ob_gzhandler") or ob_start();
header('Last-Modified: ' . $gmdate . " GMT"); 
if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $time){  
  header("HTTP/1.0 304 Not Modified");  
} else {  
  header('Content-type: text/css');
  readfile($css);  
}

