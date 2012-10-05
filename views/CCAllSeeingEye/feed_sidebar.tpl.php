<div class='box'>
<h4><?=t('Categories')?></h4>
<ul>
  <?php foreach($categories as $category): ?>
  <li><a class='no-style' href='<?=$category->url?>'><?=$category->name?></a></li>
  <?php endforeach; ?>
</ul>
</div>

<div class='box'>
<h4><?=t('Related')?></h4>
<ul>
  <?php foreach($related as $site): ?>
  <li><a class='no-style' href='<?=$site->url?>'><?=$site->name?></a></li>
  <?php endforeach; ?>
</ul>
</div>

<?php foreach($feeds['sites'] as $site): ?>
<div class='box'>
<h4><?=t('Latest')?></h4>
<ul>
  <?php if(isset($site->items)): ?>
  <?php foreach($site->items as $item): ?>
  <li><a class='no-style' href='<?=$item->permalink?>' title='<?=''.time_diff($item->date).' ago'?>'><?=$item->title?></a></li>
  <?php endforeach; ?>
  <?php endif; ?>
</ul>
</div>
<?php endforeach; ?>

