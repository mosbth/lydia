<h1><?=t('User Control Panel')?></h1>
<p><?=t('Manage your account.')?></p>
<ul>
  <li><a href='<?=create_url('user/profile')?>'><?=t('User profile')?></a>
  <li><a href='<?=create_url('user/change-password')?>'><?=t('Change password')?></a>
</ul>

<p><?=t('Created !time ago.', array('!time' => time_diff($user['created'])))?></p>
<?php if($user['updated']): ?>
  <p><?=t('Last updated !time ago.', array('!time' => time_diff($user['updated'])))?></p>
<?php endif; ?>
<p><?=t('You are member of !number group(s).', array('!number' => count($user['groups'])))?></p>
<ul>
<?php foreach($user['groups'] as $group): ?>
  <li><?=$group['name']?>
<?php endforeach; ?>
</ul>
