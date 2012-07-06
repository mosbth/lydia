<article class='blog-list'>

<section>
<h1><?=t('RSS aggregation though the All-Seeing-Eye')?></h1>
<p><?=t('The module CCAllSeeingEye is a Lydia application which does RSS aggregation. Make an app-class who use this class, create a config.php-file to choose your feeds and customize the views to adapt it.')?></p>
</section>

<?php foreach($feeds['items'] as $item): ?>
<section class='blog-post'>
  <span class='published'><a rel='nofollow' href='<?=$item->site->url?>'><?=$item->site->name?> &bull; <?=time_diff($item->date)?></a></span>
  <h2><a class='no-style' href='<?=$item->permalink?>'><?=$item->title?></a></h2>
  <p><?=$item->content?> &hellip;</p>
  <p class='category'><a href='<?=$item->site->category->url?>'><?=t('Category:') . ' ' . $item->site->category->name?></a></p>
</section>
<?php endforeach; ?>

</article>
