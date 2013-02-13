<h1>Create the root user</h1>

<p>Set the email, username and password for the root user. </p>

<?php if($rootUser): ?>
<p class='info'>You have created (<?=$rootUser['created']?>) a root user with the username: <b><?=$rootUser['acronym']?></b>.</p>
<?php else: ?>
<?=$form->GetHTML()?>
<?php endif; ?>

<p>
<a href='<?=create_url(null, 'step3')?>'>&laquo; Back</a>&nbsp;&nbsp;&nbsp;
<a href='<?=create_url(null, 'step4')?>'>Reload this step to check status again...</a>&nbsp;&nbsp;&nbsp;
<?php if($rootUser): ?>
<a href='<?=create_url(null, 'step5')?>'>Continue &raquo;</a>
<?php endif; ?>
</p>
