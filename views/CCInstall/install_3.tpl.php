<h1>Verify that the default database is available</h1>

<p>Lets check to see if we can connect to the default database.</p>

<p>Your default database is (change this in <code>site/config.php</code>):</p>

<pre><code><?=$dsn?></code></pre>

<?php 
global $ly;
$db_works = $ly->db === null ? false : true;
?> 

<?php if($db_works): ?>

<p class='success'>Great, I can connect to the database!</p>

<?php else: ?>

<p class='error'>Failed. I can NOT connect to the database. Review your database connection settings.</p>

<?php endif; ?>


<p>
<a href='<?=create_url(null, 'step2')?>'>&laquo; Back</a>&nbsp;&nbsp;&nbsp;
<a href='<?=create_url(null, 'step3')?>'>Reload this step to check status again...</a>&nbsp;&nbsp;&nbsp;
<?php if($db_works): ?>
<a href='<?=create_url(null, 'step4')?>'>Continue &raquo;</a>
<?php endif; ?>
</p>
