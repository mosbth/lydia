<?php
/**
 * Site configuration, this file is change by user per site.
 *
 */

/*
 * Set level of error reporting
 */
error_reporting(-1); 

/*
 * Define session name
 */
$ly->cfg['session_name'] = preg_replace('/[:\.\/-_]/', '', $_SERVER["SERVER_NAME"]);

/*
 * Define server timezone
 */
$ly->cfg['timezone'] = 'Europe/Stockholm';

/*
 * Define internal character encoding to UTF-8
 */
$ly->cfg['character_encoding'] = 'UTF-8';
