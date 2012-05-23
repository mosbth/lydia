<h4>Kategorier</h4>
<ul>
<?php foreach($categories as $category): ?>
<li><a class='no-style' href='<?=$category->url?>'><?=$category->name?></a></li>
<?php endforeach; ?>
</ul>

<h4>I samma kategori ingår</h4>
<ul>
<?php foreach($related as $site): ?>
<li><a class='no-style' href='<?=$site->url?>'><?=$site->name?></a></li>
<?php endforeach; ?>
</ul>

<?php foreach($feeds['sites'] as $site): ?>
<h4>Senaste inläggen</h4>
<ul>
<?php foreach($site->items as $item): ?>
<li><a class='no-style' href='<?=$item->permalink?>' title='<?=''.time_diff($item->date).' ago'?>'><?=$item->title?></a></li>
<?php endforeach; ?>
</ul>
<?php endforeach; ?>

