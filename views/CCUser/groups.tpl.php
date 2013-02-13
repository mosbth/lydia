<?php include($header); ?>

<h2><?=t('Groups')?></h2>

<p><?=t('You are member of !number group(s).', array('!number' => count($user['groups'])))?></p>
<ul>
<?php foreach($user['groups'] as $group): ?>
  <li><?=$group['name']?>
<?php endforeach; ?>
</ul>
