<h1>Verify that the directory <code>site/data</code> is writable</h1>

<p>Lets ensure that the directory <code>site/data</code> is writable by the webserver. 
This is the (only) place where Lydia and Lydia modules needs to be able to write and create files.</p>

<p>The path to your site-directory is (this is defined in <code>index.php</code>):</p>

<pre><code><?=LYDIA_SITE_PATH.'/data'?></code></pre>

<?php 
$is_directory = is_dir(LYDIA_SITE_PATH.'/data'); 
$is_writable  = is_writable(LYDIA_SITE_PATH.'/data'); 

?> 

<?php if($is_directory && $is_writable): ?>

<p class='success'>Success. The data directory exists and is writable.</p>

<?php elseif($is_directory): ?>

<p class='error'>Failed. The data directory exists but it is NOT writable.</p>

<p>Correct this by changing the permissions on the directory.</p>

<blockquote>
<code>cd lydia; chmod 777 site/data</code>
</blockquote>

<?php else: ?>

<p class='error'>Failed. The data directory does NOT exist.</p>

<p>Are you sure that you have the correct LYDIA_SITE_PATH set in <code>index.php</code>? It is currently set to:</p>

<p><code><?=LYDIA_SITE_PATH?></code></p>

<p>Try to create the directory.</p>

<blockquote>
<code>cd <?=LYDIA_SITE_PATH?>; mkdir data; chmod 777 data</code>
</blockquote>

<?php endif; ?>


<p>
<a href='<?=create_url(null, 'step1')?>'>&laquo; Back</a>&nbsp;&nbsp;&nbsp;
<a href='<?=create_url(null, 'step2')?>'>Reload this step to check status again...</a>&nbsp;&nbsp;&nbsp;
<?php if($is_directory && $is_writable): ?>
<a href='<?=create_url(null, 'step3')?>'>Continue &raquo;</a>
<?php endif; ?>
</p>
