<?php if($content != null):?>

<article class='ly-blog' itemscope itemtype='http://schema.org/Article'>

  <header class='ly-blog-post-header'>
    <h1 itemprop='name headline'><?=esc($content['title'])?></h1>
    <?php 
      $date = $order_by_updated ? $content['updated'] : $content['created']; 
      $what = $order_by_updated ? t('Updated') : t('Published'); 
    ?>
    <p class='ly-blog-post-meta-header'>
      <?=t('By')?> <span class="vcard author"><span class="fn" itemprop='author' itemscope itemtype='http://schema.org/Person'><span itemprop='name'><!--<a href='http://dbwebb.se/v2/author/mos' rel='author'>--><?=$content['owner_name']?><!--</a>--></span></span></span>.
        <?=$what?> <time itemprop='datePublished' datetime='<?=$date?>'><?=format_date($date)?></time>
      </span>
    </p>
  </header>

  <?=$content['data_filtered']?>

  <footer class='ly-blog-post-footer'>
    <p class='ly-blog-post-meta-footer'><a href='<?=create_url(null, m('category'), $content['category_key'])?>'><?=t('Category: @category_name', array('@category_name' => $content['category_title']))?></a>
    <?php if($user_is_admin || $contents->CurrentUserIsOwner()): ?>
    | <a href='<?=create_url("content/edit/{$content['id']}")?>'><?=t('edit')?></a></p>
    <?php endif; ?>
  </footer>

  <?=get_author_byline($author)?>

</article>

<?php else:?>
<p><?=t('No such post.')?></p>
<?php endif;?>


