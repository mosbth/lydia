<article class='ly-page'>

  <header class='ly-page-header'>
    <h1><?=esc($content['title'])?></h1>
    <p class='ly-blog-post-meta-header'><?=$content->PublishAction()?> <time datetime='<?=$content->PublishTime()?>'><?=format_date($content->PublishTime())?></time>
    <?=t('by !owner', array('!owner' => $content['owner_name']))?>
    <?php if($user_is_admin || $content->CurrentUserIsOwner()): ?>
    | <a href='<?=create_url("content/edit/{$content['id']}")?>'><?=t('edit')?></a>
    <?php endif; ?>
    </p>
  </header>

  <?=$content['data_filtered']?>

  <?php if($user_is_admin || $content->CurrentUserIsOwner()): ?>
  <footer class='ly-page-footer'>    
    <p class='ly-page-meta-footer'><a href='<?=create_url("content/edit/{$content['id']}")?>'><?=t('edit')?></a></p>
  </footer>
  <?php endif; ?>

</article>
