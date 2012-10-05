<article class='blog list'>

<?php foreach($feeds['categories'] as $category): ?>
<section class='post'>
<h1><?=$category['name']?></h1>
<p><?=$category['description']?></p>
</section>

<?php if(isset($feeds['items'])): ?>
<?php foreach($feeds['items'] as $item): ?>
<section class='post'>
  <span class='published'><a class='no-style' rel='nofollow' href='<?=$item->site->url?>'><?=$item->site->name?> &bull; <?=time_diff($item->date)?></a></span>
  <h2><a class='no-style' href='<?=$item->permalink?>'><?=$item->title?></a></h2>
  <p><?=$item->content?> &hellip;</p>
</section>
<?php endforeach; ?>
<?php endif; ?>

<?php endforeach; ?>

</article>
