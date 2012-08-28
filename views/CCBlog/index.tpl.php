<?php if($contents != null):?>

<article class='blog list'>

<?php if(isset($category)): ?>
<h1><?=esc($category['title'])?></h1>
<p><?=esc($category['description'])?></p>
<?php elseif(isset($intro)): ?>
<?=$intro?>
<?php endif; ?>


<?php foreach($contents as $val):?>
<section class='post'>

  <?php if($order_by_updated): ?>
  <span class='updated'><?=t('Last updated on !updated', array('!updated' => $val['updated']))?></span>
  <?php else: ?>
  <span class='published'><?=t('On !created by !owner', array('!created' => $val['created'], '!owner' => $val['owner']))?></span>
  <?php endif; ?>
  
  <h2><a href='<?=create_url(null, $val['key'])?>'><?=esc($val['title'])?></a></h2>

  <?php if($post_format_short): ?>
  <?=$val['data_short_filtered']?>
    <?php if($val['data_has_more']): ?>  
    <p class='readmore'><a href='<?=create_url(null, $val['key'])?>'><?=t('Read more »')?></a></p>
    <?php endif; ?>
  <?php else: ?>
  <?=$val['data_filtered']?>
  <?php endif; ?>

  <p class='footer'>
    <a href='<?=create_url(null, 'category', $val['category_key'])?>'><?=t('Category: @category_name', array('@category_name' => $val['category_title']))?></a>
    <?php if($user_is_admin_or_owner): ?>
    | <a href='<?=create_url("content/edit/{$val['id']}")?>'><?=t('edit')?></a>
    <?php endif; ?>
  </p>

</section>
<?php endforeach; ?>
</article>

<?php else:?>
<p><?=t('No posts exists.')?></p>
<?php endif;?>

