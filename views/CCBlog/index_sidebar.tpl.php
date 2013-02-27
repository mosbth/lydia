<?php 
foreach($sidebar_contains as $val) {
  switch($val) {


    case 'intro':
?>
<div class='box'>
<h4><?=$intro['title']?></h4>
<p><?=$intro['content']?></p>
</div>
<?php
    break;


    case 'toc':
?>
<?php if(!empty($content['toc_formatted'])): ?>
<div id='toc' class='box'>
<h4>Innehållsförteckning</h4>
<?=$content['toc_formatted']?>
</div>
<?php endif;?>
<?php
    break;


    case 'latest':
?>
<div class='box'>
<h4>Senaste inläggen</h4>
<ul>
<?php foreach($contents as $val):?>
<li><a href='<?=create_url(null, $val['key'])?>'><?=esc($val['title'])?></a></li>
<?php endforeach; ?>
</ul>
</div>
<?php
    break;


    case 'categories':
?>
<div class='box'>
<h4>Kategorier</h4>
<ul>
<?php foreach($categories as $val):?>
<li><a href='<?=create_url(null, 'category', $val['key'])?>'><?=esc($val['title'])?></a> (<?=$val['items']?>)</li>
<?php endforeach; ?>
</ul>
</div>
<?php
    break;

  }
}
