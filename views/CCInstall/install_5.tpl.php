<h1>Login as the root user</h1>

<p>You need to login as root to carry out the final installation steps.</p>

<?=$form->GetHTML()?>

<?php if($isAdmin): ?>
<p><em>You are logged in as the root user and can proceed with the final steps.</em></p>
<?php else: ?>
<p><em>You need to login as root before you can proceed with the final steps.</em></p>
<?php endif; ?>

<p>
<a href='<?=create_url(null, 'step4')?>'>&laquo; Back</a>&nbsp;&nbsp;&nbsp;
<a href='<?=create_url(null, 'step5')?>'>Reload this step to check status again...</a>&nbsp;&nbsp;&nbsp;
<?php if($isAdmin): ?>
<a href='<?=create_url(null, 'step6')?>'>Continue &raquo;</a>
<?php endif; ?>
</p>
