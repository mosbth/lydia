<article class='blog list'>

<?php foreach($feeds['sites'] as $site): ?>

<section class='post'>
  <h1><?=$site->name?></h1>
  <p><?=$site->description?></p>
  <p class='footer'><?=t('Details for !site:', array('!site'=>$site->name))?> <a href='<?=$site->siteurl?>'><?=t('Website')?></a> &bull; <a href='<?=$site->feedurl?>'><?=t('RSS feed')?></a></p>
</section>

<?php foreach($feeds['items'] as $item): ?>
<section class='post'>
  <span class='published'><a rel='nofollow' href='<?=$item->site->url?>'><?=$item->site->name?> &bull; <?=time_diff($item->date)?></a></span>
  <h2><a href='<?=$item->permalink?>'><?=$item->title?></a></h2>
  <p><?=$item->content?> &hellip;</p>
  <p class='footer'><a href='<?=$item->site->category->url?>'><?=t('Category:') . ' ' . $item->site->category->name?></a></p>
</section>
<?php endforeach; ?>

<?php endforeach; ?>

</article>
