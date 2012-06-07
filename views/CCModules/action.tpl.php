<h1><?=t('Results from action')?></h1>

<p><?=t('The action was <strong>!action</strong>.', array('!action'=>$action))?> 
<?=t('The following modules were affected.')?></p>

<table>
<caption><?=t('Results from each module affected.')?></caption>
<thead>
  <tr><th><?=t('Module')?></th><th><?=t('Result')?></th></tr>
</thead>
<tbody>
<?php $output = null; ?>
<?php foreach($modules as $module): ?>
  <tr><td><?=esc($module['name'])?></td><td><div class='<?=$module['result'][0]?>'><?=esc($module['result'][1])?></div></td></tr>
  <?php $output .= $module['output']; ?>
<?php endforeach; ?>
</tbody>
</table>

<?php if($output): ?>
<h2><?=t('Output from action')?></h2>
<textarea class='code nowrap'><?=esc($output)?></textarea>
<?php endif; ?>