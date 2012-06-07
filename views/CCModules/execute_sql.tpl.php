<h1><?=t('Execute SQL')?></h1>

<p>
<?=t('Enter SQL commands to DROP/CREATE/ALTER tables and INSERT/UPDATE rows.')?> 
<?=t('Use the same format as you got as a result from exporting the database.')?>
</p>

<?=$form->GetHTML()?>

<?php if(is_null($results)): ?>
<p><?=t('Press to execute SQL batch.')?></p>
<?php else: ?>

<h2><?=t('Results from SQL batch')?></h2>
<table>
<caption><?=t('Results from SQL batch.')?></caption>
<thead>
  <tr><th><?=t('Detail')?></th><th><?=t('Value')?></th></tr>
</thead>
<tbody>
  <tr><td><?=t('Total rows')?></td><td><?=$results['meta']['total_rows']?></td></tr>
  <tr><td><?=t('Comments')?></td><td><?=$results['meta']['comments']?></td></tr>
  <tr><td><?=t('DROP')?></td><td><?=$results['meta']['drop']?></td></tr>
  <tr><td><?=t('CREATE')?></td><td><?=$results['meta']['create']?></td></tr>
  <tr><td><?=t('ALTER')?></td><td><?=$results['meta']['alter']?></td></tr>
  <tr><td><?=t('INSERT')?></td><td><?=$results['meta']['insert']?></td></tr>
  <tr><td><?=t('UPDATE')?></td><td><?=$results['meta']['update']?></td></tr>
  <tr><td><?=t('Unknown')?></td><td><?=$results['meta']['unknown']?></td></tr>
  <tr><td><?=t('Success')?></td><td><?=$results['meta']['success']?></td></tr>
  <tr><td><?=t('Failed')?></td><td><?=$results['meta']['failed']?></td></tr>
  <tr><td><?=t('Rows affected')?></td><td><?=$results['meta']['rowcount']?></td></tr>
  <?php if(isset($results['meta']['failed_query'])): ?>
  <tr><td><?=t('Failed query')?></td><td class='code'><?=esc($results['meta']['failed_query'])?></td></tr>
  <?php endif; ?>
</tbody>
</table>
<?php endif; ?>