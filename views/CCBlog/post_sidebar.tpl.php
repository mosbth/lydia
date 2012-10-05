<?php if($contents != null):?>

<?php if(!empty($content['toc_formatted'])): ?>
<div id='toc' class='box'>
<h4>Innehållsförteckning</h4>
<?=$content['toc_formatted']?>
</div>
<?php endif;?>


<div class='box'>
<h4>Senaste inläggen</h4>
<ul>

<?php foreach($contents as $val):?>
<li><a href='<?=create_url(null, $val['key'])?>'><?=esc($val['title'])?></a></li>
<?php endforeach; ?>
</ul>
</div>

<div class='box'>
<h4>Kategorier</h4>
<ul>

<?php foreach($categories as $val):?>
<li><a href='<?=create_url(null, 'category', $val['key'])?>'><?=esc($val['title'])?></a> (<?=$val['items']?>)</li>
<?php endforeach; ?>
</ul>
</div>

<?php else:?>
<p><?=t('No posts exists.')?></p>
<?php endif;?>
