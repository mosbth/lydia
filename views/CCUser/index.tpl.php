<?php include($header); ?>

<h2><?=t('Overview')?></h2>

<p>Hi <?=$user['name']?>.</p>

<p>Current login, last login.</p>

<p><?=t('Created !time ago.', array('!time' => time_diff($user['created'])))?></p>
<?php if($user['updated']): ?>
  <p><?=t('Last updated !time ago.', array('!time' => time_diff($user['updated'])))?></p>
<?php endif; ?>


<p><a href='<?=create_url(null, 'logout')?>'>Logout</a> &bull; 
<?php if($user->IsAdmin()): ?>
<a href='<?=create_url('acp')?>'><?=t('Admin Control Panel')?></a>
<?php endif; ?>
</p>
