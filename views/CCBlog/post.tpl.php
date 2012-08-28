<?php if($content != null):?>

<article class='blog'>
  <section class='post'>
    <h1><?=esc($content['title'])?></h1>
    <span class='published'><?=t('On !created by !owner', array('!created' => $content['created'], '!owner' => $content['owner']))?></span>
    <?=$content['data_filtered']?>
    <p class='footer'>
      <a href='<?=create_url(null, 'category', $content['category_key'])?>'><?=t('Category: @category_name', array('@category_name' => $content['category_title']))?></a>
      <?php if($user_is_admin_or_owner): ?>
      | <a href='<?=create_url("content/edit/{$content['id']}")?>'><?=t('edit')?></a>
      <?php endif; ?>
    </p>
  </section>
</article>

<?php else:?>
<p><?=t('No such post.')?></p>
<?php endif;?>

