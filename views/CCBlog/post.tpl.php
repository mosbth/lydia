<?php if($content != null):?>

<article class='ly-blog'>

  <header class='ly-blog-post-header'>
    <h1><?=esc($content['title'])?></h1>
    <?php 
      $date = $order_by_updated ? $content['updated'] : $content['created']; 
      $what = $order_by_updated ? t('Updated') : t('Published'); 
    ?>
    <p class='ly-blog-post-meta-header'><?=$what?> <time datetime='<?=$date?>'><?=format_date($date)?></time>
    <?=t('by !owner', array('!owner' => $content['owner_name']))?></p>
  </header>

  <?=$content['data_filtered']?>

  <footer class='ly-blog-post-footer'>
    <p class='ly-blog-post-meta-footer'><a href='<?=create_url(null, 'category', $content['category_key'])?>'><?=t('Category: @category_name', array('@category_name' => $content['category_title']))?></a>
    <?php if($user_is_admin || $contents->CurrentUserIsOwner()): ?>
    | <a href='<?=create_url("content/edit/{$content['id']}")?>'><?=t('edit')?></a></p>
    <?php endif; ?>
  </footer>

</article>

<?php else:?>
<p><?=t('No such post.')?></p>
<?php endif;?>


