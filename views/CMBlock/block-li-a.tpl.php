<?php 
$nofollow = (isset($nofollow) && $nofollow) ? " rel='nofollow'" : null; 
$class = isset($class) ? " {$class}" : null;
$title = isset($title_href) ? "<a href='{$title_href}' title='{$title_href_title}'>{$title}</a> " : $title;
?>

<div class='box <?=$class?>'>
<h4><?=$title?></h4>
<ul>
<?php foreach($items as $val): ?>
  <li><a href='<?=create_url($val['href'])?>' title='<?=esc($val['title'])?>'<?=$nofollow?>><?=esc($val['text'])?></a></li>
<?php endforeach; ?>
</ul>
</div>
