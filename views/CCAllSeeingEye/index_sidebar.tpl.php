<div class='box'>
<h4><?=t('Categories')?></h4>
<ul>
<?php foreach($categories as $category): ?>
<li><a class='no-style' href='<?=$category->url?>'><?=$category->name?></a></li>
<?php endforeach; ?>
</ul>
</div>

<div class='box'>
<?php foreach($categories as $category): ?>
<h4><a class='no-style' href='<?=$category->url?>'><?=$category->name?></a></h4>
<ul>
<?php foreach($feeds['categories'][$category->key]['items'] as $item): ?>
<li><a class='no-style' href='<?=$item->site->url?>' title='<?=''.time_diff($item->date).' ago'?>'><?=$item->site->name?>: <?=$item->title?></a></li>
<?php endforeach; ?>
</ul>
<?php endforeach; ?>
</div>

