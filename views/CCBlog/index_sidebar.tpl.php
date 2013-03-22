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
<h4><?=t('Table of content')?></h4>
<?=$content['toc_formatted']?>
</div>
<?php endif;?>
<?php
    break;


    case 'current':
?>
<div class='box'>
<h4><?=t('Now displaying')?></h4>
<ul>
<?php foreach($contents as $val):?>
<li><a href='<?=create_url(null, $val['key'])?>'><?=esc($val['title'])?></a></li>
<?php endforeach; ?>
</ul>
<?=pagination_next($first_page, $current_page, $last_page, $first_hit, $last_hit, $total_hits, $pagination_url)?>
</div>
<?php
    break;


    case 'categories':
?>
<div class='box'>
<h4><?=t('Categories')?></h4>
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
