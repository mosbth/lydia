<h1>Verify the server environment</h1>

<p>I'll run a few test to see what kind of environment you have.</p>

<?php 
$memory_limit = ini_get('memory_limit');
$gettext      = function_exists('gettext'); 
$safemode     = ini_get('safe_mode'); 
$pdo          = class_exists('PDO');
$pdo_sqlite   = in_array("sqlite", PDO::getAvailableDrivers());
$magic_quotes = ini_get('magic_quotes_gpc') || ini_get('magic_quotes_runtime') || ini_get('magic_quotes_sybase');

$problems = !$gettext || $safemode || !$pdo || !$pdo_sqlite || $magic_quotes;

?> 

<p class='info'>Your PHP version is: <?=PHP_VERSION?>. Max memory limit is <?=$memory_limit?>. Operating system is <?=PHP_OS?>.</p>

<?php if($gettext): ?>
<p class='success'>You have gettext enabled and can use the multilanguage support.</p>
<?php else: ?>
<p class='info'>You have NOT gettext enabled and can NOT use the multilanguage support.</p>
<?php endif; ?>

<?php if($safemode): ?>
<p class='error'>You have safe mode enabled on this server. Lydia has not been NOT tested on a server with safe mode enabled.</p>
<?php else: ?>
<p class='success'>Safe mode is NOT enabled on this server.</p>
<?php endif; ?>

<?php if($pdo && $pdo_sqlite): ?>
<p class='success'>You have PDO enabled and you have SQLite as available driver for PDO.</p>
<?php elseif($pdo): ?>
<p class='error'>You have PDO enabled but is lacking support for the SQLite PDO driver.</p>
<?php else: ?>
<p class='error'>Database driver PDO is not available.</p>
<?php endif; ?>

<?php if($magic_quotes): ?>
<p class='error'>You have magic quotes enabled on this server. Lydia has not been NOT tested on a server with safe mode enabled.</p>
<?php else: ?>
<p class='success'>Magic quotes are disabled.</p>
<?php endif; ?>


<?php if($problems): ?>
<p class='error'>Your environment does not fully match the need of Lydia. You may try to correct the issues or try anyway. But do not say I did not warn you.</p>
<?php else: ?>
<p class='success'><b>Nice!</b> It seems like this is an environment where Lydia may enjoy herself.</p>
<?php endif; ?>


<p>
<a href='<?=create_url(null, 'index')?>'>&laquo; Back</a>&nbsp;&nbsp;&nbsp;
<a href='<?=create_url(null, 'step1')?>'>Reload this step to check status again...</a>&nbsp;&nbsp;&nbsp;
<a href='<?=create_url(null, 'step2')?>'>Continue &raquo;</a>
</p>
