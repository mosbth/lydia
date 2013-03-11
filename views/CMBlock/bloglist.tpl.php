<?php
$base_url = isset($base_url) ? $base_url : null;
?>

<div class='box'>
<h4><a href='<?=create_url($title_url)?>'><?=$title?></a></h4>
</div>

<section class='ly-blog'>

<?php foreach($contents as $val):?>
<article class='ly-blog-post'>

  <header class='ly-blog-post-header'>
    <?php $date = $order_by_updated ? $val['updated'] : $val['created']; ?>
    <span class='ly-blog-post-meta-header'><time datetime='<?=$date?>'><?=format_date($date)?></time></span>
    <h2><a href='<?=create_url($base_url, $val['key'])?>'><?=esc($val['title'])?></a></h2>
  </header>

  <?=$val['data_short_filtered']?>
  <p class='ly-blog-post-readmore'><a href='<?=create_url($base_url, $val['key'])?>'><?=t('Read more »')?></a></p>

  <footer class='ly-blog-post-footer'>
    <p class='ly-blog-post-meta-footer'><a href='<?=create_url($base_url, 'category', $val['category_key'])?>'><?=t('Category: @category_name', array('@category_name' => $val['category_title']))?></a>
    <?php if($user_is_admin || $contents->CurrentUserIsOwner()): ?>
    | <a href='<?=create_url("content/edit/{$val['id']}")?>'><?=t('edit')?></a></p>
    <?php endif; ?>
  </footer>

</article>
<?php endforeach; ?>

</section>

