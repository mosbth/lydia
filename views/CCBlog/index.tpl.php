<section class='ly-blog'>

<header class='ly-blog-header'>
  <?php if(isset($category)): ?>
  <h1><?=t('Category: @title', array('@title' => $category['title']))?></h1>
  <p><?=esc($category['description'])?></p>

  <?php elseif(isset($intro)): ?>
  <h1><?=$intro['title']?></h1>
  <p><?=$intro['content']?></p>
  <?php endif; ?>
</header>

<?php if($contents != null):?>
<?php foreach($contents as $val):?>
<article class='ly-blog-post'>

  <header class='ly-blog-post-header'>
    <?php $date = $order_by_updated ? $val['updated'] : $val['created']; ?>
    <span class='ly-blog-post-meta-header'><time datetime='<?=$date?>'><?=format_date($date)?></time></span>
    <h2><a href='<?=create_url(null, $val['key'])?>'><?=esc($val['title'])?></a></h2>
  </header>

  <?php if($post_format_short): ?>
  <?=$val['data_short_filtered']?>
    <?php if($val['data_has_more']): ?>  
    <p class='ly-blog-post-readmore'><a href='<?=create_url(null, $val['key'])?>'><?=t('Read more »')?></a></p>
    <?php endif; ?>
  <?php else: ?>
  <?=$val['data_filtered']?>
  <?php endif; ?>

  <footer class='ly-blog-post-footer'>
    <p class='ly-blog-post-meta-footer'><a href='<?=create_url(null, 'category', $val['category_key'])?>'><?=t('Category: @category_name', array('@category_name' => $val['category_title']))?></a>
    <?php if($user_is_admin || $contents->CurrentUserIsOwner()): ?>
    | <a href='<?=create_url("content/edit/{$val['id']}")?>'><?=t('edit')?></a></p>
    <?php endif; ?>
  </footer>

</article>
<?php endforeach; ?>

<footer>
<?php if($hits): ?>
  <p><?=t('Displaying hits !first - !last from a total of !total.', array('!first' => $first_hit, '!last' => $last_hit, '!total' => $total_hits));?></p>
  <?=pagination($first_page, $current_page, $last_page, $pagination_url)?>
<?php endif; ?>
</footer>


<?php else:?>
<p><?=t('No posts exists.')?></p>
<?php endif;?>


</section>

