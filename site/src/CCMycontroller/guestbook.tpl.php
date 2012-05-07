<h1>My Guestbook</h1>
<p>Leave a message and be happy.</p>

<?=$form->GetHTML()?>

<h2>Latest messages</h2>

<?php foreach($entries as $val):?>
<div style='background-color:#f6f6f6;border:1px solid #ccc;margin-bottom:1em;padding:1em;'>
  <p>At: <?=$val['created']?></p>
  <p><?=htmlent($val['entry'])?></p>
</div>
<?php endforeach;?>
