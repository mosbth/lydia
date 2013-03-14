<section class='ly-blog'>

<header class='ly-blog-header'>
  <?php if(isset($category)): ?>
  <h1><?=esc($category['title'])?></h1>
  <p><?=esc($category['description'])?></p>

  <?php elseif(isset($feed)): ?>
  <h1><?=$feed['title']?></h1>
  <p><?=$feed['description']?></p>
  <p><?=t('Details for !site:', array('!site'=>$feed['title']))?> <a href='<?=$feed['siteurl']?>'><?=t('Website')?></a> &bull; <a href='<?=$feed['feedurl']?>'><?=t('RSS feed')?></a></p>

  <?php elseif(isset($intro)): ?>
  <h1><?=$intro['title']?></h1>
  <p><?=$intro['description']?></p>
  <?php endif; ?>
</header>

<?php if(!empty($feeds['items'])): ?>
<?php foreach($feeds['items'] as $item): ?>
<article class='ly-blog-post'>

  <header class='ly-blog-post-header'>
    <span class='ly-blog-post-meta-header'><a rel='nofollow' href='<?=$item->site->url?>'><?=$item->site->name?> &bull; <time datetime='<?=$item->date?>'><?=time_diff($item->date)?></time></a></span>
    <h2><a href='<?=$item->permalink?>'><?=$item->title?></a></h2>
  </header>

  <p><?=$item->content?> &hellip;</p>

  <footer class='ly-blog-post-footer'>
    <p class='ly-blog-post-meta-footer'><a href='<?=$item->site->category->url?>'><?=t('Category:') . ' ' . $item->site->category->name?></a></p>
  </footer>

</article>

<?php endforeach; ?>
<?php endif; ?>

</section>
