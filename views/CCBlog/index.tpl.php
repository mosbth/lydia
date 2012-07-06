<?php if($contents != null):?>

<article class='blog-list'>

<?php if(isset($intro)) echo $intro; ?>

<?php foreach($contents as $val):?>
<section class='blog-post'>
  <span class='published'><?=t('On !created by !owner', array('!created' => $val['created'], '!owner' => $val['owner']))?></span>
  <h2><?=esc($val['title'])?></h2>
  <p><?=$val['data_filtered']?></p>
  <p class='edit'><a href='<?=create_url("content/edit/{$val['id']}")?>'><?=t('edit')?></a></p>
</section>
<?php endforeach; ?>
</article>

<?php else:?>
<p><?=t('No posts exists.')?></p>
<?php endif;?>

