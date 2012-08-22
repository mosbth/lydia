<?php
/**
 * Here can the site owner include own code and functions. This file is included right 
 * after the creation of $ly and its function lySiteInit() is called. You can use this to 
 * overwrite existing functions or add new ones. This is a good place to use when 
 * integrating your Lydia website with another website and want to use a session from the 
 * existing one.
 *
 * Prepend your functions with "lySite" or something other to get your own namespace.
 */

/**
 * This function is called by index.php, if defined, at the start of each page load, right 
 * after the creation of $ly.
 */
function lySiteInit() {
  echo "hej";
}