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
/*
function lySiteInit() {
  global $ly;
  
  if(isset($ly->config['extra']['phpbb_root_path'])) {
    lySiteIntegratePHPBBSession($ly->config['extra']['phpbb_root_path']);
  }
}
*/


/**
 * Sample function to integrate with a phpbb installation and lend some information 
 * on the authorized user.
 *
 * @param string $path is the install path of PHPBB.
 */
/*
function lySiteIntegratePHPBBSession($path) {
  global $ly, $phpbb_root_path, $phpEx, $user, $db, $config, $cache, $template, $auth;
  
  define('IN_PHPBB', true);
  $phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : $path;
  $phpEx = 'php'; //substr(strrchr(__FILE__, '.'), 1);
  include($phpbb_root_path . 'common.' . $phpEx);
  
  // Start session management
  $user->session_begin();
  $auth->acl($user->data);
  $user->setup();

  // Populate this user with data from phpbb user.
  if($user->data['user_id'] != ANONYMOUS && !$ly->user->IsAuthenticated()) {
    $ly->user['isAuthenticated'] = true;
    $ly->user['hasRoleAnonomous'] = false;
    $ly->user['hasRoleVisitor'] = true;
    $ly->user['id'] = 1;
    $ly->user['acronym'] = $user->data['username_clean'];      
    $ly->user['email'] = $user->data['user_email'];
    $ly->config['menus']['login']['logout']['url'] .= '&amp;sid=' . $user->data['session_id'];
  }
}
*/

