<?php if($content != null):?>

<article class='blog'>
  <section class='post'>
    <h1><?=esc($content['title'])?></h1>
    <span class='published'><?=t('On !created by !owner', array('!created' => $content['created'], '!owner' => $content['owner']))?></span>
    <p><?=$content['data_filtered']?></p>
    <p class='footer'>
      <?=t('Category: @category_name', array('@category_name' => $content['category_title']))?>
      <?php if($user_is_admin_or_owner): ?>
      | <a href='<?=create_url("content/edit/{$content['id']}")?>'><?=t('edit')?></a>
      <?php endif; ?>
    </p>
  </section>
</article>

<?php else:?>
<p><?=t('No such post.')?></p>
<?php endif;?>

