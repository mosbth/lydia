<?php $nofollow = (isset($nofollow) && $nofollow) ? " rel='nofollow'" : null; ?>

<div class='box'>
<h4><?=$title?></h4>
<ul>
<?php foreach($items as $val): ?>
  <li><a href='<?=create_url($val['href'])?>' title='<?=esc($val['title'])?>'<?=$nofollow?>><?=esc($val['text'])?></a></li>
<?php endforeach; ?>
</ul>
</div>
